<?php

namespace App\Controller;

use Bone\Mvc\Controller;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Psr\Http\Message\ServerRequestInterface;

class BaseController extends Controller
{
    /** @var Serializer */
    protected $serializer;

    public function __construct(ServerRequestInterface $request)
    {
        parent::__construct($request);
        $this->serializer = SerializerBuilder::create()->build();
        $this->disableView();
        $this->disableLayout();
    }
}