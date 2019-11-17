<?php

namespace SpringboardVR\LaravelAblyBroadcaster;

use Illuminate\Broadcasting\Broadcasters\Broadcaster;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Ably\AblyRest;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AblyBroadcaster extends Broadcaster
{
    use UseAblyChannelConventions;

    /**
     * The AblyRest SDK instance.
     *
     * @var \Ably\AblyRest
     */
    protected $ably;

    /**
     * Create a new broadcaster instance.
     *
     * @param  \Ably\AblyRest  $ably
     * @return void
     */
    public function __construct(AblyRest $ably)
    {
        $this->ably = $ably;
    }

    /**
     * Authenticate the incoming request for a given channel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function auth($request)
    {
        $channelName = $this->normalizeChannelName($request->channel_name);

        if ($this->isGuardedChannel($request->channel_name) &&
            ! $this->retrieveUser($request, $channelName)) {
            throw new AccessDeniedHttpException;
        }

        return parent::verifyUserCanAccessChannel(
            $request, $channelName
        );
    }

    /**
     * Return the valid authentication response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $result
     * @return mixed
     */
    public function validAuthenticationResponse($request, $result)
    {
        if (Str::startsWith($request->channel_name, 'private')) {
            $signature = $this->generateAblySignature($request->channel_name, $request->socket_id);
            return ['auth' => $this->getPublicToken() . ':' . $signature];
        }

        $channelName = $this->normalizeChannelName($request->channel_name);
        $userId = $this->retrieveUser($request, $channelName)->getAuthIdentifier();

        $userData = [
            'user_id' => $userId,
        ];

        if ($result) {
            $userData['user_info'] = $result;
        }

        $signature = $this->generateAblySignature($request->channel_name, $request->socket_id, $userData);

        return [
            'auth' => $this->getPublicToken() . ':' . $signature,
            'channel_data' => json_encode($userData),
        ];
    }

    /**
     * Generate the Signature for Ably auth headers
     * @param $channelName
     * @param $socketId
     * @param null $userData
     * @return string
     */
    public function generateAblySignature($channelName, $socketId, $userData = null)
    {
        $privateToken = $this->getPrivateToken();

        $signature = $socketId .':' . $channelName;

        if ($userData) {
            $signature .= ':' . json_encode($userData);
        }

        return hash_hmac('sha256',$signature, $privateToken);
    }

    /**
     * Broadcast the given event.
     *
     * @param  array  $channels
     * @param  string  $event
     * @param  array  $payload
     * @return void
     */
    public function broadcast(array $channels, $event, array $payload = [])
    {;
        foreach (self::formatChannels($channels) as $channel) {
            $ablyChannel = $this->ably->channels->get($channel);
            $ablyChannel->publish($event, $payload);
        }
    }

    /**
     * Get the Public Token Value out of the Ably key
     * @return mixed
     */
    public function getPublicToken()
    {
        return Str::before($this->ably->options->key, ':');
    }

    /**
     * Get the Private Token value out of the Ably Key
     * @return mixed
     */
    public function getPrivateToken()
    {
        return Str::after($this->ably->options->key, ':');
    }

    /**
     * Get the Ably SDK instance.
     *
     * @return \Ably\AblyRest
     */
    public function getAbly()
    {
        return $this->ably;
    }
}
