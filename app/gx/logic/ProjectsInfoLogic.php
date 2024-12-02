<?php
// +----------------------------------------------------------------------
// | admin [ 学无止境 ]
// +----------------------------------------------------------------------
// | Author: zzllbj@126.com
// +----------------------------------------------------------------------
namespace app\gx\logic;

use OpenSpout\Reader\XLSX\Reader;
use plugin\saiadmin\basic\BaseLogic;
use plugin\saiadmin\exception\ApiException;
use plugin\saiadmin\service\OpenSpoutWriter;
use plugin\saiadmin\utils\Helper;
use app\gx\model\ProjectsInfo;

/**
 * 项目信息逻辑层
 */
class ProjectsInfoLogic extends BaseLogic
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new ProjectsInfo();
    }

    /**
     * 导入数据
     */
    public function import($file)
    {
        $path = $this->getImport($file);
        $reader = new Reader();
        try {
            $reader->open($path);
            $data = [];
            foreach ($reader->getSheetIterator() as $sheet) {
                $isHeader = true;
                foreach ($sheet->getRowIterator() as $row) {
                    if ($isHeader) {
                        $isHeader = false;
                        continue;
                    }
                    $cells = $row->getCells();
                    $data[] = [
                        'name' => $cells[0]->getValue(),
                        'start_date' => $cells[1]->getValue(),
                        'end_date' => $cells[2]->getValue(),
                    ];
                }
            }
            $this->saveAll($data);
        } catch (\Exception $e) {
            throw new ApiException('导入文件错误，请上传正确的文件格式xlsx');
        }
    }

    /**
     * 导出数据
     */
    public function export($where = [])
    {
        $query = $this->search($where)->field('name,start_date,end_date');
        $data = $this->getAll($query);
        $file_name = '课题信息.xlsx';
        $header = ['课题名称', '开始时间', '结束时间'];
        $filter = [];
        $writer = new OpenSpoutWriter($file_name);
        $writer->setWidth([15, 15, 20, 15, 15, 25]);
        $writer->setHeader($header);
        $writer->setData($data, null, $filter);
        $file_path = $writer->returnFile();
        return response()->download($file_path, urlencode($file_name));
    }


}
