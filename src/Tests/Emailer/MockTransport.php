<?php

namespace DRI\SugarCRM\Tests\Emailer;

use DRI\SugarCRM\Emailer\Email;
use DRI\SugarCRM\Emailer\Transport;

/**
 * @author Emil Kilhage
 */
class MockTransport extends Transport
{

    protected $responses = array();
    protected $currentResponse;
    protected $driverClass = '\DRI\SugarCRM\Tests\Emailer\MockDriver';

    public function queueResponse(MockResponse $response)
    {
        $this->responses[] = $response;
    }

    protected function create()
    {
        /** @var MockDriver $return */
        $return = parent::create();

        $response = array_shift($this->responses);

        if (!$response) {
            throw new \LogicException("Missing Response");
        }

        $return->setCurrentResponse($response);

        return $return;
    }

}
