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
        // TODO: Implement Auth for Ably
//        if (Str::startsWith($request->channel_name, 'private')) {
//            return $this->decodeAblyResponse(
//                $request, $this->ably->socket_auth($request->channel_name, $request->socket_id)
//            );
//        }
//
//        $channelName = $this->normalizeChannelName($request->channel_name);
//
//        return $this->decodeAblyResponse(
//            $request,
//            $this->ably->presence_auth(
//                $request->channel_name, $request->socket_id,
//                $this->retrieveUser($request, $channelName)->getAuthIdentifier(), $result
//            )
//        );
    }

    /**
     * Decode the given Ably response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $response
     * @return array
     */
    protected function decodeAblyResponse($request, $response)
    {

        // TODO: Decode Ably Auth Checks
//        if (! $request->input('callback', false)) {
//            return json_decode($response, true);
//        }
//
//        return response()->json(json_decode($response, true))
//            ->withCallback($request->callback);
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
     * Get the Ably SDK instance.
     *
     * @return \Ably\AblyRest
     */
    public function getAbly()
    {
        return $this->ably;
    }
}
