<?php

namespace Task;

use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Task\Api\Client;

class TestClass
{
    private Request $request;

    public function __construct(ServerRequest $request)
    {
        $this->request = Request::factory($request);
    }

    public function getRequest(): ?Request
    {
        return $this->request;
    }

	public function helloWorld(): string
	{
	    $request = $this->getRequest();

	    $searchResults = [];

	    if ($request && $request->has('search')) {
	        $searchResults = $this->search([
	            'name' => $request->get('name', ''),
                'email' => $request->get('email', ''),
                'phone' => $request->get('phone', ''),
            ]);
        }

		return print_r($searchResults, true);
	}

    /**
     * @throws \Exception
     */
    public function search(array $searchTerms): array
    {
        $client = Client::factory();

        $method = 'GET';
        $action = 'contacts';

        $parameters = [
            'name' => '',
            'email' => '',
            'phone' => '',
        ];

        foreach ($searchTerms as $field => $value) {
            if (array_key_exists($field, $parameters)) {
                if (is_null($value)) {
                    $value = '';
                }

                $parameters[$field] = urlencode($value);
            }
        }

        $request = $client->query($method, $action, $parameters);

        if (($request instanceof ResponseInterface) && $request->getStatusCode() !== 200) {
            throw new \Exception($request->getBody()->getContents());
        } elseif (is_array($request)) {
            return $request;
        }

        return $this->processSearch($request);
    }

    private function processSearch(ResponseInterface $response): array
    {
        $responseData = $response->getBody()->getContents();

        $decoded = json_decode($responseData, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception(
                'Unexpected data type returned'
            );
        }

        return $decoded;
    }
}
