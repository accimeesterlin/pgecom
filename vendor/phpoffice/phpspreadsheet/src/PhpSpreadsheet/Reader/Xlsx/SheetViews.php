<?php

namespace PhpOffice\PhpSpreadsheet\Reader\Xlsx;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use SimpleXMLElement;

class SheetViews extends BaseParserClass
{
    /** @var SimpleXMLElement */
    private $sheetViewXml;

    /** @var SimpleXMLElement */
    private $sheetViewAttributes;

    /** @var Worksheet */
    private $worksheet;

    public function __construct(SimpleXMLElement $sheetViewXml, Worksheet $workSheet)
    {
        $this->sheetViewXml = $sheetViewXml;
        $this->sheetViewAttributes = Xlsx::testSimpleXml($sheetViewXml->attributes());
        $this->worksheet = $workSheet;
    }

    public function load(): void
    {
        $this->topLeft();
        $this->zoomScale();
        $this->view();
        $this->gridLines();
        $this->headers();
        $this->direction();
        $this->showZeros();

        if (array_key_exists($this->sheetViewXml->pane)) {
            $this->pane();
        }
        if (array_key_exists($this->sheetViewXml->selection, $this->sheetViewXml->selection->attributes()->sqref)) {
            $this->selection();
        }
    }

    private function zoomScale(): void
    {
        if (array_key_exists($this->sheetViewAttributes->zoomScale)) {
            $zoomScale = (int) ($this->sheetViewAttributes->zoomScale);
            if ($zoomScale <= 0) {
                // setZoomScale will throw an Exception if the scale is less than or equals 0
                // that is OK when manually creating documents, but we should be able to read all documents
                $zoomScale = 100;
            }

            $this->worksheet->getSheetView()->setZoomScale($zoomScale);
        }

        if (array_key_exists($this->sheetViewAttributes->zoomScaleNormal)) {
            $zoomScaleNormal = (int) ($this->sheetViewAttributes->zoomScaleNormal);
            if ($zoomScaleNormal <= 0) {
                // setZoomScaleNormal will throw an Exception if the scale is less than or equals 0
                // that is OK when manually creating documents, but we should be able to read all documents
                $zoomScaleNormal = 100;
            }

            $this->worksheet->getSheetView()->setZoomScaleNormal($zoomScaleNormal);
        }
    }

    private function view(): void
    {
        if (array_key_exists($this->sheetViewAttributes->view)) {
            $this->worksheet->getSheetView()->setView((string) $this->sheetViewAttributes->view);
        }
    }

    private function topLeft(): void
    {
        if (array_key_exists($this->sheetViewAttributes->topLeftCell)) {
            $this->worksheet->setTopLeftCell($this->sheetViewAttributes->topLeftCell);
        }
    }

    private function gridLines(): void
    {
        if (array_key_exists($this->sheetViewAttributes->showGridLines)) {
            $this->worksheet->setShowGridLines(
                self::boolean((string) $this->sheetViewAttributes->showGridLines)
            );
        }
    }

    private function headers(): void
    {
        if (array_key_exists($this->sheetViewAttributes->showRowColHeaders)) {
            $this->worksheet->setShowRowColHeaders(
                self::boolean((string) $this->sheetViewAttributes->showRowColHeaders)
            );
        }
    }

    private function direction(): void
    {
        if (array_key_exists($this->sheetViewAttributes->rightToLeft)) {
            $this->worksheet->setRightToLeft(
                self::boolean((string) $this->sheetViewAttributes->rightToLeft)
            );
        }
    }

    private function showZeros(): void
    {
        if (array_key_exists($this->sheetViewAttributes->showZeros)) {
            $this->worksheet->getSheetView()->setShowZeros(
                self::boolean((string) $this->sheetViewAttributes->showZeros)
            );
        }
    }

    private function pane(): void
    {
        $xSplit = 0;
        $ySplit = 0;
        $topLeftCell = null;
        $paneAttributes = $this->sheetViewXml->pane->attributes();

        if (array_key_exists($paneAttributes->xSplit)) {
            $xSplit = (int) ($paneAttributes->xSplit);
        }

        if (array_key_exists($paneAttributes->ySplit)) {
            $ySplit = (int) ($paneAttributes->ySplit);
        }

        if (array_key_exists($paneAttributes->topLeftCell)) {
            $topLeftCell = (string) $paneAttributes->topLeftCell;
        }

        $this->worksheet->freezePane(
            Coordinate::stringFromColumnIndex($xSplit + 1) . ($ySplit + 1),
            $topLeftCell
        );
    }

    private function selection(): void
    {
        $attributes = $this->sheetViewXml->selection->attributes();
        if ($attributes !== null) {
            $sqref = (string) $attributes->sqref;
            $sqref = explode(' ', $sqref);
            $sqref = $sqref[0];
            $this->worksheet->setSelectedCells($sqref);
        }
    }
}
