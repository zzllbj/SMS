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
use app\gx\model\UsersInfo;

/**
 * 人员信息逻辑层
 */
class UsersInfoLogic extends BaseLogic
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new UsersInfo();
    }

    /**
     * 这里实现返回的born_date字段的日期计算年龄大小
     */
    public function computerage($userlistdata): array
    {
        if (is_array($userlistdata) && isset($userlistdata['data'])) {
            foreach ($userlistdata['data'] as &$userInfo) {
                $bornDate = $userInfo['born_date'] ?? null;
                if ($bornDate) {
                    $birthDate = new \DateTime($bornDate);
                    $currentDate = new \DateTime('now');
                    $age = $currentDate->diff($birthDate)->y;
                    $userInfo['age'] = $age;
                }
            }
        }
        return $userlistdata;
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
                        'sex' => $cells[1]->getValue(),
                        'tel' => $cells[2]->getValue(),
                        'born_date' => $cells[3]->getValue(),
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
        $query = $this->search($where)->field('name,sex,tel,born_date');
        $data = $this->getAll($query);
        $file_name = '科技人员.xlsx';
        $header = ['姓名', '性别', '电话', '出生日期'];
        $filter = [];
        $writer = new OpenSpoutWriter($file_name);
        $writer->setWidth([15, 15, 20, 15, 15, 25]);
        $writer->setHeader($header);
        $writer->setData($data, null, $filter);
        $file_path = $writer->returnFile();
        return response()->download($file_path, urlencode($file_name));
    }


}
