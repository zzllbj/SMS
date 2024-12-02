<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace plugin\saiadmin\app\logic\system;

use plugin\saiadmin\app\model\system\SystemPost;
use plugin\saiadmin\basic\BaseLogic;
use plugin\saiadmin\exception\ApiException;
use plugin\saiadmin\service\OpenSpoutWriter;
use OpenSpout\Reader\XLSX\Reader;
use support\Response;

/**
 * 岗位管理逻辑层
 */
class SystemPostLogic extends BaseLogic
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new SystemPost();
    }

    /**
     * 可操作岗位
     * @param array $where
     * @return array
     */
    public function accessPost(array $where = []): array
    {
        $query = $this->search($where);
        return $this->getAll($query);
    }

    /**
     * 导入数据
     */
    public function import($file):string
    {
        $path = $this->getImport($file);
        $reader = new Reader();
        try {
            $reader->open($path);
            var_dump('文件路径：'.$path);
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
                        'name' => $cells[1]->getValue(),
                        'code' => $cells[2]->getValue(),
                        'sort' => $cells[3]->getValue(),
                        'status' => $cells[4]->getValue(),
                    ];
                    var_dump($data);

                }
            }
            $this->saveAll($data);
            return json(['code' => 200, 'msg' => '成功','data'=>'1']);
        } catch (\Exception $e) {
            throw new ApiException('导入文件错误，请上传正确的文件格式xlsx'.$e);
        }

    }

    /**
     * 导出数据
     */
    public function export($where = []):Response
    {
        $query = $this->search($where)->field('id,name,code,sort,status,create_time');
        $data = $this->getAll($query);
        $file_name = '岗位数据.xlsx';
        $header = ['编号', '岗位名称', '岗位标识', '排序', '状态', '创建时间'];
        $filter = [
            'status' => [
                ['value' => 1, 'label' => '正常'],
                ['value' => 2, 'label' => '禁用']
            ]
        ];
        $writer = new OpenSpoutWriter($file_name);
        $writer->setWidth([15, 15, 20, 15, 15, 25]);
        $writer->setHeader($header);
        $writer->setData($data, null, $filter);
        $file_path = $writer->returnFile();
        return response()->download($file_path, urlencode($file_name));
    }

}
