<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\Request;

class ExtractDataRequest
{
    private function getDataRequest(Request  $request)
    {
        $informationOrder = $request->query->get('sort');
        $informationFilter = $request->query->all();
        unset($informationFilter['sort']);

        return [$informationOrder, $informationFilter];
    }

    public function getDataOrdernation(Request $request)
    {
        [$informationOrder,] = $this->getDataRequest($request);
        return $informationOrder;
    }

    public function getDataFilter(Request $request)
    {
        //php sabe que quando o indice e vazio por isso nao preciso informar
        [, $informationFilter] = $this->getDataRequest($request);
        return $informationFilter;
    }
}