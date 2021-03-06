<?php

namespace App\Models\Adapters;

use Nesk\Rialto\Data\JsFunction;

class ParseAdapterHapagLloyd extends BaseAdapter
{
    public $adapterName = 'Hapag Lloyd';
    public $url = 'https://www.hapag-lloyd.com/en/online-business/tracing/tracing-by-container.html';

    public function processToTracking()
    {
        $this->debug('containerNumber: ' . $this->containerNumber);

        $this->debug('wait 7 s');
        $this->page->waitFor(7000);

        $this->debug('click #accept-recommended-btn-handler');
        $this->page->click('#accept-recommended-btn-handler');

        $this->debug('type table[summary="LabelledComponentTable"] input');
        $this->page->type('table[summary="LabelledComponentTable"] input', $this->containerNumber, [
            'delay' => 50
        ]);

        $this->debug('wait 1 s');
        $this->page->waitFor(1000);


        $this->debug('click table[summary="ButtonPanelTable"] button');
        $this->page->click('table[summary="ButtonPanelTable"] button');

        $this->debug('wait 5 s');
        $this->page->waitFor(5000);

        $this->debug('#statusInfo: scroll into view');

        $this->page->evaluate(JsFunction::createWithBody("
            return document.querySelector('#statusInfo').scrollIntoView();
        "));

        $this->makeScreenshot();

    }

    public function getData()
    {
        $this->debug('GET DATA');

        // TODO check it with valid Container No.

//        $data = $this->page->evaluate(JsFunction::createWithBody("
//
//            let record = {};
//            let table = document.querySelector('#detailInfo > table');
//
//            if (!table) {
//                return [record];
//            }
//
//            let cells = table.querySelectorAll('tr:last-child > td');
//
//            if (!cells.length) {
//                return [record];
//            }
//
//            let elements = [
//
//                {
//                    prop: 'type',
//                    node: document.getElementById('st_cntrTpszNm')
//                },
//
//                {
//                    prop: 'date',
//                    node: cells[3]
//                },
//
//                {
//                    prop: 'event',
//                    node: cells[1]
//                },
//
//                {
//                    prop: 'place',
//                    node: cells[2]
//                }
//            ];
//
//            elements.forEach(function(el, index) {
//
//                if (el.node instanceof Element) {
//                  if (el.prop === 'type') {
//                    record[el.prop] = el.node.innerHTML.split('<br>')[1];
//                  } else if (el.prop === 'date') {
//                    record[el.prop] = new Date(Date.parse(el.node.textContent)).toDateString();
//                  } else {
//                    record[el.prop] = el.node.textContent.trim();
//                  }
//                }
//
//
//            });
//
//            return [record];
//
//        "));

        $data = [];

        return $this->appendAdapterName($data);
    }
}
