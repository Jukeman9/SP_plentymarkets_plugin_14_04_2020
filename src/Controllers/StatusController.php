<?php
/**
 * Created for plentymarkets-plugin.
 * User: jakim <pawel@jakimowski.info>
 * Date: 01/06/2020
 */

namespace SwiatPrzesylek\Controllers;


use Plenty\Plugin\Controller;
use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Log\Loggable;
use SwiatPrzesylek\Contracts\StatusMapRepositoryContract;
use SwiatPrzesylek\Repositories\StatusRepository;

class StatusController extends Controller
{
    use Loggable;

    private $statusRepository;
    private $statusMapRepository;

    public function __construct(StatusRepository $statusRepository, StatusMapRepositoryContract $statusMapRepository)
    {
        $this->statusRepository = $statusRepository;
        $this->statusMapRepository = $statusMapRepository;
    }

    public function get(Request $request)
    {
        return [
            'mapping' => $this->statusMapRepository->getMapping(),
            'spStatuses' => $this->statusRepository->getSpStatuses(),
            'orderStatuses' => $this->statusRepository->getOrderStatuses(),
        ];
    }

    public function post(Request $request)
    {
        $spStatuses = array_column($this->statusRepository->getSpStatuses(), 'id');
        foreach ($request->input() as $key => $value) {
            if (in_array($key, $spStatuses)) {
                $this->statusMapRepository->saveMap($key, $value);
            }
        }
    }
}