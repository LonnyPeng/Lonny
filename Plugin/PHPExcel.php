<?php

if (!defined('PHPEXCEL_ROOT')) {
    define('PHPEXCEL_ROOT', dirname(__FILE__) . '/');
    require(PHPEXCEL_ROOT . 'PHPExcel/Autoloader.php');
}

/**
 * e.g
 * 
 * use PHPExcel;
 *
 * PHPExcel::export($title, $data);
 */
class PHPExcel
{
    private $uniqueID;
    private $properties;
    private $security;
    private $workSheetCollection = array();
    private $calculationEngine;
    private $activeSheetIndex = 0;
    private $namedRanges = array();
    private $cellXfSupervisor;
    private $cellXfCollection = array();
    private $cellStyleXfCollection = array();
    private $hasMacros = false;
    private $macrosCode;
    private $macrosCertificate;
    private $ribbonXMLData;
    private $ribbonBinObjects;

    public function hasMacros()
    {
        return $this->hasMacros;
    }

    public function setHasMacros($hasMacros = false)
    {
        $this->hasMacros = (bool) $hasMacros;
    }

    public function setMacrosCode($MacrosCode = null)
    {
        $this->macrosCode=$MacrosCode;
        $this->setHasMacros(!is_null($MacrosCode));
    }

    public function getMacrosCode()
    {
        return $this->macrosCode;
    }

    public function setMacrosCertificate($Certificate = null)
    {
        $this->macrosCertificate=$Certificate;
    }

    public function hasMacrosCertificate()
    {
        return !is_null($this->macrosCertificate);
    }

    public function getMacrosCertificate()
    {
        return $this->macrosCertificate;
    }

    public function discardMacros()
    {
        $this->hasMacros=false;
        $this->macrosCode=null;
        $this->macrosCertificate=null;
    }

    public function setRibbonXMLData($Target = null, $XMLData = null)
    {
        if (!is_null($Target) && !is_null($XMLData)) {
            $this->ribbonXMLData = array('target' => $Target, 'data' => $XMLData);
        } else {
            $this->ribbonXMLData = null;
        }
    }

    public function getRibbonXMLData($What = 'all') //we need some constants here...
    {
        $ReturnData = null;
        $What = strtolower($What);
        switch ($What){
            case 'all':
                $ReturnData = $this->ribbonXMLData;
                break;
            case 'target':
            case 'data':
                if (is_array($this->ribbonXMLData) && array_key_exists($What, $this->ribbonXMLData)) {
                    $ReturnData = $this->ribbonXMLData[$What];
                }
                break;
        }

        return $ReturnData;
    }

    public function setRibbonBinObjects($BinObjectsNames = null, $BinObjectsData = null)
    {
        if (!is_null($BinObjectsNames) && !is_null($BinObjectsData)) {
            $this->ribbonBinObjects = array('names' => $BinObjectsNames, 'data' => $BinObjectsData);
        } else {
            $this->ribbonBinObjects = null;
        }
    }

    private function getExtensionOnly($ThePath)
    {
        return pathinfo($ThePath, PATHINFO_EXTENSION);
    }

    public function getRibbonBinObjects($What = 'all')
    {
        $ReturnData = null;
        $What = strtolower($What);
        switch($What) {
            case 'all':
                return $this->ribbonBinObjects;
                break;
            case 'names':
            case 'data':
                if (is_array($this->ribbonBinObjects) && array_key_exists($What, $this->ribbonBinObjects)) {
                    $ReturnData=$this->ribbonBinObjects[$What];
                }
                break;
            case 'types':
                if (is_array($this->ribbonBinObjects) &&
                    array_key_exists('data', $this->ribbonBinObjects) && is_array($this->ribbonBinObjects['data'])) {
                    $tmpTypes=array_keys($this->ribbonBinObjects['data']);
                    $ReturnData = array_unique(array_map(array($this, 'getExtensionOnly'), $tmpTypes));
                } else {
                    $ReturnData=array(); // the caller want an array... not null if empty
                }
                break;
        }
        return $ReturnData;
    }

    public function hasRibbon()
    {
        return !is_null($this->ribbonXMLData);
    }

    public function hasRibbonBinObjects()
    {
        return !is_null($this->ribbonBinObjects);
    }

    public function sheetCodeNameExists($pSheetCodeName)
    {
        return ($this->getSheetByCodeName($pSheetCodeName) !== null);
    }

    public function getSheetByCodeName($pName = '')
    {
        $worksheetCount = count($this->workSheetCollection);
        for ($i = 0; $i < $worksheetCount; ++$i) {
            if ($this->workSheetCollection[$i]->getCodeName() == $pName) {
                return $this->workSheetCollection[$i];
            }
        }

        return null;
    }

    public function __construct()
    {
        $this->uniqueID = uniqid();
        $this->calculationEngine = new PHPExcel_Calculation($this);

        // Initialise worksheet collection and add one worksheet
        $this->workSheetCollection = array();
        $this->workSheetCollection[] = new PHPExcel_Worksheet($this);
        $this->activeSheetIndex = 0;

        // Create document properties
        $this->properties = new PHPExcel_DocumentProperties();

        // Create document security
        $this->security = new PHPExcel_DocumentSecurity();

        // Set named ranges
        $this->namedRanges = array();

        // Create the cellXf supervisor
        $this->cellXfSupervisor = new PHPExcel_Style(true);
        $this->cellXfSupervisor->bindParent($this);

        // Create the default style
        $this->addCellXf(new PHPExcel_Style);
        $this->addCellStyleXf(new PHPExcel_Style);
    }

    public function __destruct()
    {
        $this->calculationEngine = null;
        $this->disconnectWorksheets();
    }

    public function disconnectWorksheets()
    {
        $worksheet = null;
        foreach ($this->workSheetCollection as $k => &$worksheet) {
            $worksheet->disconnectCells();
            $this->workSheetCollection[$k] = null;
        }
        unset($worksheet);
        $this->workSheetCollection = array();
    }

    public function getCalculationEngine()
    {
        return $this->calculationEngine;
    }

    public function getProperties()
    {
        return $this->properties;
    }

    public function setProperties(PHPExcel_DocumentProperties $pValue)
    {
        $this->properties = $pValue;
    }

    public function getSecurity()
    {
        return $this->security;
    }

    public function setSecurity(PHPExcel_DocumentSecurity $pValue)
    {
        $this->security = $pValue;
    }

    public function getActiveSheet()
    {
        return $this->getSheet($this->activeSheetIndex);
    }

    public function createSheet($iSheetIndex = null)
    {
        $newSheet = new PHPExcel_Worksheet($this);
        $this->addSheet($newSheet, $iSheetIndex);
        return $newSheet;
    }

    public function sheetNameExists($pSheetName)
    {
        return ($this->getSheetByName($pSheetName) !== null);
    }

    public function addSheet(PHPExcel_Worksheet $pSheet, $iSheetIndex = null)
    {
        if ($this->sheetNameExists($pSheet->getTitle())) {
            throw new PHPExcel_Exception(
                "Workbook already contains a worksheet named '{$pSheet->getTitle()}'. Rename this worksheet first."
            );
        }

        if ($iSheetIndex === null) {
            if ($this->activeSheetIndex < 0) {
                $this->activeSheetIndex = 0;
            }
            $this->workSheetCollection[] = $pSheet;
        } else {
            // Insert the sheet at the requested index
            array_splice(
                $this->workSheetCollection,
                $iSheetIndex,
                0,
                array($pSheet)
            );

            // Adjust active sheet index if necessary
            if ($this->activeSheetIndex >= $iSheetIndex) {
                ++$this->activeSheetIndex;
            }
        }

        if ($pSheet->getParent() === null) {
            $pSheet->rebindParent($this);
        }

        return $pSheet;
    }

    public function removeSheetByIndex($pIndex = 0)
    {

        $numSheets = count($this->workSheetCollection);
        if ($pIndex > $numSheets - 1) {
            throw new PHPExcel_Exception(
                "You tried to remove a sheet by the out of bounds index: {$pIndex}. The actual number of sheets is {$numSheets}."
            );
        } else {
            array_splice($this->workSheetCollection, $pIndex, 1);
        }
        // Adjust active sheet index if necessary
        if (($this->activeSheetIndex >= $pIndex) &&
            ($pIndex > count($this->workSheetCollection) - 1)) {
            --$this->activeSheetIndex;
        }

    }

    public function getSheet($pIndex = 0)
    {
        if (!isset($this->workSheetCollection[$pIndex])) {
            $numSheets = $this->getSheetCount();
            throw new PHPExcel_Exception(
                "Your requested sheet index: {$pIndex} is out of bounds. The actual number of sheets is {$numSheets}."
            );
        }

        return $this->workSheetCollection[$pIndex];
    }

    public function getAllSheets()
    {
        return $this->workSheetCollection;
    }

    public function getSheetByName($pName = '')
    {
        $worksheetCount = count($this->workSheetCollection);
        for ($i = 0; $i < $worksheetCount; ++$i) {
            if ($this->workSheetCollection[$i]->getTitle() === $pName) {
                return $this->workSheetCollection[$i];
            }
        }

        return null;
    }

    public function getIndex(PHPExcel_Worksheet $pSheet)
    {
        foreach ($this->workSheetCollection as $key => $value) {
            if ($value->getHashCode() == $pSheet->getHashCode()) {
                return $key;
            }
        }

        throw new PHPExcel_Exception("Sheet does not exist.");
    }

    public function setIndexByName($sheetName, $newIndex)
    {
        $oldIndex = $this->getIndex($this->getSheetByName($sheetName));
        $pSheet = array_splice(
            $this->workSheetCollection,
            $oldIndex,
            1
        );
        array_splice(
            $this->workSheetCollection,
            $newIndex,
            0,
            $pSheet
        );
        return $newIndex;
    }

    public function getSheetCount()
    {
        return count($this->workSheetCollection);
    }

    public function getActiveSheetIndex()
    {
        return $this->activeSheetIndex;
    }

    public function setActiveSheetIndex($pIndex = 0)
    {
        $numSheets = count($this->workSheetCollection);

        if ($pIndex > $numSheets - 1) {
            throw new PHPExcel_Exception(
                "You tried to set a sheet active by the out of bounds index: {$pIndex}. The actual number of sheets is {$numSheets}."
            );
        } else {
            $this->activeSheetIndex = $pIndex;
        }
        return $this->getActiveSheet();
    }

    public function setActiveSheetIndexByName($pValue = '')
    {
        if (($worksheet = $this->getSheetByName($pValue)) instanceof PHPExcel_Worksheet) {
            $this->setActiveSheetIndex($this->getIndex($worksheet));
            return $worksheet;
        }

        throw new PHPExcel_Exception('Workbook does not contain sheet:' . $pValue);
    }

    public function getSheetNames()
    {
        $returnValue = array();
        $worksheetCount = $this->getSheetCount();
        for ($i = 0; $i < $worksheetCount; ++$i) {
            $returnValue[] = $this->getSheet($i)->getTitle();
        }

        return $returnValue;
    }

    public function addExternalSheet(PHPExcel_Worksheet $pSheet, $iSheetIndex = null)
    {
        if ($this->sheetNameExists($pSheet->getTitle())) {
            throw new PHPExcel_Exception("Workbook already contains a worksheet named '{$pSheet->getTitle()}'. Rename the external sheet first.");
        }

        // count how many cellXfs there are in this workbook currently, we will need this below
        $countCellXfs = count($this->cellXfCollection);

        // copy all the shared cellXfs from the external workbook and append them to the current
        foreach ($pSheet->getParent()->getCellXfCollection() as $cellXf) {
            $this->addCellXf(clone $cellXf);
        }

        // move sheet to this workbook
        $pSheet->rebindParent($this);

        // update the cellXfs
        foreach ($pSheet->getCellCollection(false) as $cellID) {
            $cell = $pSheet->getCell($cellID);
            $cell->setXfIndex($cell->getXfIndex() + $countCellXfs);
        }

        return $this->addSheet($pSheet, $iSheetIndex);
    }

    public function getNamedRanges()
    {
        return $this->namedRanges;
    }

    public function addNamedRange(PHPExcel_NamedRange $namedRange)
    {
        if ($namedRange->getScope() == null) {
            // global scope
            $this->namedRanges[$namedRange->getName()] = $namedRange;
        } else {
            // local scope
            $this->namedRanges[$namedRange->getScope()->getTitle().'!'.$namedRange->getName()] = $namedRange;
        }
        return true;
    }

    public function getNamedRange($namedRange, PHPExcel_Worksheet $pSheet = null)
    {
        $returnValue = null;

        if ($namedRange != '' && ($namedRange !== null)) {
            // first look for global defined name
            if (isset($this->namedRanges[$namedRange])) {
                $returnValue = $this->namedRanges[$namedRange];
            }

            // then look for local defined name (has priority over global defined name if both names exist)
            if (($pSheet !== null) && isset($this->namedRanges[$pSheet->getTitle() . '!' . $namedRange])) {
                $returnValue = $this->namedRanges[$pSheet->getTitle() . '!' . $namedRange];
            }
        }

        return $returnValue;
    }

    public function removeNamedRange($namedRange, PHPExcel_Worksheet $pSheet = null)
    {
        if ($pSheet === null) {
            if (isset($this->namedRanges[$namedRange])) {
                unset($this->namedRanges[$namedRange]);
            }
        } else {
            if (isset($this->namedRanges[$pSheet->getTitle() . '!' . $namedRange])) {
                unset($this->namedRanges[$pSheet->getTitle() . '!' . $namedRange]);
            }
        }
        return $this;
    }

    public function getWorksheetIterator()
    {
        return new PHPExcel_WorksheetIterator($this);
    }

    public function copy()
    {
        $copied = clone $this;

        $worksheetCount = count($this->workSheetCollection);
        for ($i = 0; $i < $worksheetCount; ++$i) {
            $this->workSheetCollection[$i] = $this->workSheetCollection[$i]->copy();
            $this->workSheetCollection[$i]->rebindParent($this);
        }

        return $copied;
    }

    public function __clone()
    {
        foreach ($this as $key => $val) {
            if (is_object($val) || (is_array($val))) {
                $this->{$key} = unserialize(serialize($val));
            }
        }
    }

    public function getCellXfCollection()
    {
        return $this->cellXfCollection;
    }

    public function getCellXfByIndex($pIndex = 0)
    {
        return $this->cellXfCollection[$pIndex];
    }

    public function getCellXfByHashCode($pValue = '')
    {
        foreach ($this->cellXfCollection as $cellXf) {
            if ($cellXf->getHashCode() == $pValue) {
                return $cellXf;
            }
        }
        return false;
    }

    public function cellXfExists($pCellStyle = null)
    {
        return in_array($pCellStyle, $this->cellXfCollection, true);
    }

    public function getDefaultStyle()
    {
        if (isset($this->cellXfCollection[0])) {
            return $this->cellXfCollection[0];
        }
        throw new PHPExcel_Exception('No default style found for this workbook');
    }

    public function addCellXf(PHPExcel_Style $style)
    {
        $this->cellXfCollection[] = $style;
        $style->setIndex(count($this->cellXfCollection) - 1);
    }

    public function removeCellXfByIndex($pIndex = 0)
    {
        if ($pIndex > count($this->cellXfCollection) - 1) {
            throw new PHPExcel_Exception("CellXf index is out of bounds.");
        } else {
            // first remove the cellXf
            array_splice($this->cellXfCollection, $pIndex, 1);

            // then update cellXf indexes for cells
            foreach ($this->workSheetCollection as $worksheet) {
                foreach ($worksheet->getCellCollection(false) as $cellID) {
                    $cell = $worksheet->getCell($cellID);
                    $xfIndex = $cell->getXfIndex();
                    if ($xfIndex > $pIndex) {
                        // decrease xf index by 1
                        $cell->setXfIndex($xfIndex - 1);
                    } elseif ($xfIndex == $pIndex) {
                        // set to default xf index 0
                        $cell->setXfIndex(0);
                    }
                }
            }
        }
    }

    public function getCellXfSupervisor()
    {
        return $this->cellXfSupervisor;
    }

    public function getCellStyleXfCollection()
    {
        return $this->cellStyleXfCollection;
    }

    public function getCellStyleXfByIndex($pIndex = 0)
    {
        return $this->cellStyleXfCollection[$pIndex];
    }

    public function getCellStyleXfByHashCode($pValue = '')
    {
        foreach ($this->cellStyleXfCollection as $cellStyleXf) {
            if ($cellStyleXf->getHashCode() == $pValue) {
                return $cellStyleXf;
            }
        }
        return false;
    }

    public function addCellStyleXf(PHPExcel_Style $pStyle)
    {
        $this->cellStyleXfCollection[] = $pStyle;
        $pStyle->setIndex(count($this->cellStyleXfCollection) - 1);
    }

    public function removeCellStyleXfByIndex($pIndex = 0)
    {
        if ($pIndex > count($this->cellStyleXfCollection) - 1) {
            throw new PHPExcel_Exception("CellStyleXf index is out of bounds.");
        } else {
            array_splice($this->cellStyleXfCollection, $pIndex, 1);
        }
    }

    public function garbageCollect()
    {
        // how many references are there to each cellXf ?
        $countReferencesCellXf = array();
        foreach ($this->cellXfCollection as $index => $cellXf) {
            $countReferencesCellXf[$index] = 0;
        }

        foreach ($this->getWorksheetIterator() as $sheet) {
            // from cells
            foreach ($sheet->getCellCollection(false) as $cellID) {
                $cell = $sheet->getCell($cellID);
                ++$countReferencesCellXf[$cell->getXfIndex()];
            }

            // from row dimensions
            foreach ($sheet->getRowDimensions() as $rowDimension) {
                if ($rowDimension->getXfIndex() !== null) {
                    ++$countReferencesCellXf[$rowDimension->getXfIndex()];
                }
            }

            // from column dimensions
            foreach ($sheet->getColumnDimensions() as $columnDimension) {
                ++$countReferencesCellXf[$columnDimension->getXfIndex()];
            }
        }

        // remove cellXfs without references and create mapping so we can update xfIndex
        // for all cells and columns
        $countNeededCellXfs = 0;
        $map = array();
        foreach ($this->cellXfCollection as $index => $cellXf) {
            if ($countReferencesCellXf[$index] > 0 || $index == 0) { // we must never remove the first cellXf
                ++$countNeededCellXfs;
            } else {
                unset($this->cellXfCollection[$index]);
            }
            $map[$index] = $countNeededCellXfs - 1;
        }
        $this->cellXfCollection = array_values($this->cellXfCollection);

        // update the index for all cellXfs
        foreach ($this->cellXfCollection as $i => $cellXf) {
            $cellXf->setIndex($i);
        }

        // make sure there is always at least one cellXf (there should be)
        if (empty($this->cellXfCollection)) {
            $this->cellXfCollection[] = new PHPExcel_Style();
        }

        // update the xfIndex for all cells, row dimensions, column dimensions
        foreach ($this->getWorksheetIterator() as $sheet) {
            // for all cells
            foreach ($sheet->getCellCollection(false) as $cellID) {
                $cell = $sheet->getCell($cellID);
                $cell->setXfIndex($map[$cell->getXfIndex()]);
            }

            // for all row dimensions
            foreach ($sheet->getRowDimensions() as $rowDimension) {
                if ($rowDimension->getXfIndex() !== null) {
                    $rowDimension->setXfIndex($map[$rowDimension->getXfIndex()]);
                }
            }

            // for all column dimensions
            foreach ($sheet->getColumnDimensions() as $columnDimension) {
                $columnDimension->setXfIndex($map[$columnDimension->getXfIndex()]);
            }

            // also do garbage collection for all the sheets
            $sheet->garbageCollect();
        }
    }

    public function getID()
    {
        return $this->uniqueID;
    }

    /**
     * [export 导出]
     * @Author   Lonny
     * @Email    lonnypeng@baogongpo.com
     * @DateTime 2018-12-12
     * @param    array                   $title [description]
     * @param    array                   $data  [description]
     * @return   [type]                         [description]
     */
    public static function export($title = array(), $data = array())
    {
        $fileName = date('YmdHis') . '.xls';

        //创建PHPExcel对象，注意，不能少了\
        $PHPExcel = new self();
        $objProps = $PHPExcel->getProperties();

        if (is_string(reset($title))) {
            $title = self::cellNumber($title);
        }

        $PHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(15); //调整行高
        $withArr = array();

        foreach($title as $key => $row) {
            $PHPExcel->getActiveSheet()->getStyle($row[0] . '1')->getAlignment()->setVertical('center');//垂直居中

            $withArr[$key] = self::exportLength($row[1]);

            $PHPExcel->getActiveSheet()->getStyle($row[0] . '1')->getFont()->setBold(true); //设置加粗
            $PHPExcel->setActiveSheetIndex(0)->setCellValue($row[0] . '1', $row[1]);
        }

        $i = 2;
        foreach ($data as $key => $row) {
            foreach ($title as $k => $r) {
                $PHPExcel->getActiveSheet()->getStyle($r[0] . $i)->getAlignment()->setVertical('center');//垂直居中
                
                $withArr[$k] = max($withArr[$k], self::exportLength($row[$k]));

                $PHPExcel->setActiveSheetIndex(0)->setCellValue($r[0] . $i, $row[$k] . "\t");
            }

            $i++;
        }

        foreach($title as $key => $row) {
            $PHPExcel->getActiveSheet()->getColumnDimension($row[0])->setWidth($withArr[$key]);//设置列宽度
        }
        
        ob_end_clean();//清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');
        $objWriter->save('php://output'); //文件通过浏览器下载

        return false;
    }

    /**
     * [import 导入]
     * @Author   Lonny
     * @Email    lonnypeng@baogongpo.com
     * @DateTime 2019-04-28
     * @param    string                  $path [description]
     * @return   [type]                        [description]
     */
    public static function import($path = "")
    {
        if (!file_exists($path)) {
            return false;
        }

        $ext = pathinfo($path, PATHINFO_EXTENSION);
        if ($ext == 'xls') {
            $PHPReader = new \PHPExcel_Reader_Excel5();
        } elseif ($ext == 'xlsx') {
            $PHPReader = new \PHPExcel_Reader_Excel2007();
        } else {
            return false;
        }

        $PHPExcel = $PHPReader->load($path);
        $currentSheet = $PHPExcel->getSheet(0);
        $highestRow = $currentSheet->getHighestRow(); //取得总行数
        $highestColumm = $currentSheet->getHighestColumn(); // 取得总列数

        $data = array();
        for ($i = 1; $i <= $highestRow; $i++) {
            $row = array();

            for ($j = 'A'; $j < $highestColumm; $j++) { 
                $k = "{$j}{$i}";
                $row[$k] = trim($currentSheet->getCell($k)->getValue());
            }

            $data[$i] = $row;
        }

        return $data;
    }

    /**
     * [execlDate description]
     * @Author   Lonny
     * @Email    lonnypeng@baogongpo.com
     * @DateTime 2019-04-28
     * @param    string                  $time [description]
     * @return   [type]                        [description]
     */
    public static function execlDate($time = '')
    {
        if (preg_match("/^[\d]{5}$/", $time)) {
            $strtotime = intval(($time - 25569) * 3600 * 24) - 8 * 3600; //转换成1970年以来的秒数 时区相差8小时的
            return date('Y-m-d H:i:s', $strtotime);
        } else {
            return $time;
        }
    }

    /**
     * [exportLength execl 列宽度]
     * @Author   Lonny
     * @Email    lonnypeng@baogongpo.com
     * @DateTime 2019-02-25
     * @param    string                  $str [description]
     * @return   [type]                       [description]
     */
    public static function exportLength($str = '')
    {
        $length = strlen($str);

        $number = $length - strlen(preg_replace("/\d/", '', $str)); //1
        $lower = $length - strlen(preg_replace("/[a-z]/", '', $str)); //1
        $capital = $length - strlen(preg_replace("/[A-Z]/", '', $str)); //2
        $other = $length - strlen(preg_replace("/ |\n|\r|}|\)|\-|\.|\?|<|\(|{|>|;|\/|=|\\$|&|,|\"|“|”|©|！|\*/", '', $str)); //1
        $zh = mb_strlen(preg_replace("/\d|[a-z]|[A-Z]| |\n|\r|}|\)|\-|\.|\?|<|\(|{|>|;|\/|=|\\$|&|,|\"|“|”|©|！|\*/", '', $str), "UTF-8"); //2

        $l = ceil($number * 1 + $lower * 1 + $capital * 2 + $other * 1 + $zh * 2);

        return $l + 4;
    }

    /**
     * [cellNumber [A-Z] - [65-90]]
     * @Author   Lonny
     * @Email    lonnypeng@baogongpo.com
     * @DateTime 2018-12-14
     * @param    array                   $titles [description]
     * @return   [type]                        [description]
     */
    public static function cellNumber($titles = array())
    {
        if (!$titles) {
            return false;
        }

        $number = 65; //A
        $str = "";

        foreach ($titles as $key => $title) {
            if ($number > 90) {
                if ($str) {
                    $n = false;
                    $str_data = array();
                    for ($i=0; $i < strlen($str); $i++) {
                        $val = substr($str, 0 - $i - 1, 1);

                        if ($n) {
                            $str_data[] = $val;
                        } else {
                            $val = ord($val);
                            $val++;

                            if ($val <= 90) {
                                $str_data[] = chr($val);
                                $n = true;
                            } elseif ($i == strlen($str) - 1) {
                                $str_data[] = chr(65);
                                $str_data[] = chr(65);
                            } else {
                                $str_data[] = chr(65);
                            }
                        }
                    }

                    $str_data = array_reverse($str_data);

                    $str = implode("", $str_data);
                } else {
                    $str = chr(65);
                }

                $number = 65;
            }

            $titles[$key] = array(
                $str . chr($number),
                $title,
            );

            $number++;
        }

        return $titles;
    }
}
