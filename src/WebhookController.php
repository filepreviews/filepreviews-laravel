<?php

namespace FilePreviews\Laravel;

use Event;
use Exception;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\Response;

class WebhookController extends Controller
{
    /**
     * Handle a FilePreviews webhook call.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleWebhook()
    {
        $payload = $this->getJsonPayload();

        if (! $this->existsOnFilePreviews($payload['id'])) {
            return;
        }

        $method = 'handle'.ucfirst($payload['status']);

        if (method_exists($this, $method)) {
            return $this->{$method}($payload);
        } else {
            return $this->missingMethod();
        }
    }

    /**
     * Verify with FilePreviews that the event is genuine.
     *
     * @param  string  $id
     * @return bool
     */
    protected function existsOnFilePreviews($id)
    {
        try {
            return ! is_null(App::make('FilePreviews')->retrieve($id));
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Handle a successfully generated preview.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleSuccess(array $payload)
    {
        Event::fire('filepreviews.success', [$payload]);
        return new Response('Webhook Handled', 200);
    }

    /**
     * Handle an error generating preview.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleError(array $payload)
    {
        Event::fire('filepreviews.error', [$payload]);
        return new Response('Webhook Handled', 200);
    }

    /**
     * Get the JSON payload for the request.
     *
     * @return array
     */
    protected function getJsonPayload()
    {
        return (array) json_decode(Request::getContent(), true);
    }

    /**
    * Handle calls to missing methods on the controller.
    *
    * @param  array   $parameters
    * @return mixed
    */
    public function missingMethod($parameters = [])
    {
        return new Response;
    }
}
