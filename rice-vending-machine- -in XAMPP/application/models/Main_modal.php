<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Main_modal extends MY_Model
{
    public function login($row)
    {
        ini_set('max_execution_time', 0);

        $imagesPath = $this->config->item('images');
        $videosPath = $this->config->item('videos');

        if(! is_dir('uploads/')) {
            mkdir(FCPATH . 'uploads/', 0777, true);

            $htmlFileContent = "<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>";
            file_put_contents('uploads/' . 'index.html', $htmlFileContent);
        }

        if(! is_dir($imagesPath)) {
            mkdir(FCPATH . $imagesPath, 0777, true);

            $htmlFileContent = "<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>";
            file_put_contents($imagesPath . 'index.html', $htmlFileContent);
        }

        if(! is_dir($videosPath)) {
            mkdir(FCPATH . $videosPath, 0777, true);

            $htmlFileContent = "<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>";
            file_put_contents($videosPath . 'index.html', $htmlFileContent);
        }

        $image = !empty($row['logo']) ? basename($row['logo']) : '';
        $home_video = !empty($row['home_video']) ? basename($row['home_video']) : '';
        $filling_video = !empty($row['filling_video']) ? basename($row['filling_video']) : '';

        $machine = [
            'api_key'       => !empty($row['api_key']) ? $row['api_key'] : '',
            'serial_number' => !empty($row['serial_number']) ? $row['serial_number'] : '',
            'area'          => !empty($row['area']) ? $row['area'] : '',
            'image'         => $image,
            'home_video'    => $home_video,
            'filling_video' => $filling_video,
            'm_type'        => !empty($row['m_type']) ? $row['m_type'] : 'Print voucher'
        ];

        $this->db->trans_start();

        foreach ($this->db->list_tables() as $table) {
            if(! in_array($table, ['site_settings'])) $this->db->empty_table($table);
        }

        if($this->db->insert('machine', $machine)) {
            if(!empty($row['logo'])) {
                file_put_contents($imagesPath.$image, file_get_contents($row['logo']));
            }
            if(!empty($row['home_video'])) {
                file_put_contents($videosPath.$home_video, file_get_contents($row['home_video']));
            }
            if(!empty($row['filling_video'])) {
                file_put_contents($videosPath.$filling_video, file_get_contents($row['filling_video']));
            }
        }

        if(!empty($row['varieties'])) {
            foreach ($row['varieties'] as $variety) {
                $varietyInsert = [
                    'id'          => $variety['id'],
                    'v_name'      => $variety['v_name'],
                    'description' => $variety['description'],
                    'container'   => $variety['container'],
                    'image'       => !empty($variety['image']) ? basename($variety['image']) : ''
                ];

                $check = $this->db->insert('varieties', $varietyInsert);

                if($check && !empty($variety['image'])) {
                    file_put_contents($imagesPath.$varietyInsert['image'], file_get_contents($variety['image']));
                }
            }
        }

        if(!empty($row['quantities'])) {
            foreach ($row['quantities'] as $quantity) {
                $quantityInsert = [
                    'id'        => $quantity['id'],
                    'v_id'      => $quantity['v_id'],
                    'price'     => $quantity['price'],
                    'unit_value'=> $quantity['unit_value'],
                    'unit_id'   => $quantity['unit_id'],
                    'currency'  => $quantity['currency'],
                    'image'     => !empty($quantity['image']) ? basename($quantity['image']) : ''
                ];

                $check = $this->db->insert('quantities', $quantityInsert);

                if($check && !empty($quantity['image'])) {
                    file_put_contents($imagesPath.$quantityInsert['image'], file_get_contents($quantity['image']));
                }
            }
        }

        $this->db->trans_complete();

		return $this->db->trans_status();
    }

    public function transaction(&$transaction)
    {
        $veriety = $this->db->get_where('varieties', ['id' => $transaction['v_id']])->row_array();

        $this->db->trans_start();

        $this->db->insert('transactions', $transaction);
        $trans_id = $this->db->insert_id();

        if($transaction['t_type'] === 'Fill up') {
            $update['avail_qty']= $veriety['avail_qty'] + $transaction['quantity'];
            $this->db->where(['id' => $transaction['v_id']])->update('varieties', $update);
        }

        if($transaction['t_type'] === 'Fill out') {
            $update['avail_qty']= $veriety['avail_qty'] - $transaction['quantity'];
            $this->db->where(['id' => $transaction['v_id']])->update('varieties', $update);
        }

        if($trans_id && $transaction['t_type'] === 'Fill up') {
            $apiCheck = send_curl_request('machine/transaction', 'post', $transaction, $this->machine['api_key']);

            if($apiCheck['status']) {
                $this->db->where(['id' => $trans_id])->update('transactions', [
                    'updated_to_cloud'  => 1,
                    'updated_date'      => date('Y-m-d'),
                    'updated_time'      => date('H:i:s')
                ]);
            }
        }

        $this->db->trans_complete();

		return $this->db->trans_status();
    }

    public function sync_report()
    {
        $transactions = $this->db->get_where('transactions', ['updated_to_cloud' => 0])->result_array();
        if($transactions) {
            $this->db->trans_start();

            foreach ($transactions as $transaction) {
                $apiCheck = send_curl_request('machine/transaction', 'post', [
                    'v_id'          => $transaction['v_id'],
                    'quantity'      => $transaction['quantity'],
                    't_type'        => $transaction['t_type'],
                    'created_date'  => $transaction['created_date'],
                    'created_time'  => $transaction['created_time']
                ], $this->machine['api_key']);

                if($apiCheck['status']) {
                    $this->db->where(['id' => $transaction['id']])->update('transactions', [
                        'updated_to_cloud'  => 1,
                        'updated_date'      => date('Y-m-d'),
                        'updated_time'      => date('H:i:s')
                    ]);
                }
            }

            $this->db->trans_complete();

            return $this->db->trans_status();
        }

        return true;
    }
}