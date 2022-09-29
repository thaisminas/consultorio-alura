<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\Request;

class ExtractDataRequest
{
    private function getDataRequest(Request  $request): array
    {
        $queryString = $request->query->all();
        $dataOrdernation = array_key_exists('sort', $queryString)
            ? $queryString['sort']
            :null;
        unset($queryString['sort']);

        $currentPage = array_key_exists('page', $queryString)
            ? $queryString['page']
            : 1;
        unset($queryString['page']);
        $itemPerPage = array_key_exists('itensPorPagina', $queryString)
            ? $queryString['itensPorPagina']
            : 5;
        unset($queryString['itensPorPagina']);

        return [$queryString, $dataOrdernation, $currentPage, $itemPerPage];
    }

    public function getDataOrdernation(Request $request)
    {
        [, $informationOrder] = $this->getDataRequest($request);
        return $informationOrder;
    }

    public function getDataFilter(Request $request)
    {
        //php sabe que quando o indice e vazio por isso nao preciso informar
        [$informationFilter, ] = $this->getDataRequest($request);
        return $informationFilter;
    }

    public function getDataPage(Request $request)
    {
        [, , $currentPage, $itemPerPage] = $this->getDataRequest($request);
        return [$currentPage, $itemPerPage];
    }
}