<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Field_model extends CI_Model {

    /**
     * Константы для состояния игры
     */
    const WAITING = 0;
    const READY = 1;
    const END = 2;

    /**
     * Создание игры
     */
    public function create_game()
    {
        $this->load->database();

        $game_id = $_GET['game_id'];

        $i = rand(1, 2);

        $data = array(
            'game_id' => $game_id,
            'status' => self::WAITING,
            'map' => '[ [ [], [], [] ], [ [], [], [] ], [ [], [], [] ] ]',
            'whose_turn' => $i
        );
        $this->db->insert('games', $data);
    }

    /**
     * Добавление игроков
     */
    public function insert_user()
    {
        $this->load->database();

        $user_id = $_GET['user_id'];
        $game_id = $_GET['game_id'];

        $this->db->select('COUNT(user_id)');
        $this->db->from('gamesHasUsers');
        $this->db->where('game_id', $game_id);
        $query = $this->db->get();
        $res = $query->result_array();

        if($res[0]['COUNT(user_id)'] < 2) {
            $this->db->select('marker');
            $this->db->from('gamesHasUsers');
            $this->db->where('game_id', $game_id);
            $query = $this->db->get();
            $res = $query->result_array();

            if(empty($res[0]['marker']) or $res[0]['marker'] == 0) {
                $data = array(
                    'user_id' => $user_id,
                    'game_id' => $game_id,
                    'marker' => 1
                );
                $this->db->insert('gamesHasUsers', $data);
            } elseif($res[0]['marker'] == 1) {
                $data = array(
                    'user_id' => $user_id,
                    'game_id' => $game_id,
                    'marker' => 2
                );
                $this->db->insert('gamesHasUsers', $data);
            }
        }
    }

    /**
     * Получение маркера
     *
     * @return mixed - возвращает маркер
     */
    public function getMarker()
    {
        $this->load->database();

        $user_id = $_GET['user_id'];
        $game_id = $_GET['game_id'];

        $this->db->select('marker');
        $this->db->from('gamesHasUsers');
        $this->db->where('user_id', $user_id);
         $this->db->where('game_id', $game_id);
        $query = $this->db->get();
        $res = $query->result_array();

        if(isset($res[0]['marker']))
        {
            return $res[0]['marker'];
        }
    }

    /**
     * Запрос данных карты
     *
     * @return mixed - возвращает массив
     */
    public function getMapJSON()
    {
        $this->load->database();

        $game_id = $_GET['game_id'];

        $this->db->select('map');
        $this->db->from('games');
        $this->db->where('game_id', $game_id);
        $query = $this->db->get();
        $res = $query->result_array();

        return $res[0]['map'];
    }

    /**
     * Ход игроков
     */
    public function move()
    {
        $this->load->database();

        $marker = $_GET['marker'];
        $game_id = $_GET['game_id'];
        $user_id = $_GET['user_id'];

        $i = $_GET['i'];
        $j = $_GET['j'];

        $this->db->select('map');
        $this->db->from('games');
        $this->db->where('game_id', $game_id);
        $query = $this->db->get();
        $res = $query->result_array();

        $mapRes = $res[0]['map'];

        $map = json_decode($mapRes);

        $map[$i][$j] = $marker;

        $map = json_encode($map);

        $this->db->select('marker');
        $this->db->from('gamesHasUsers');
        $this->db->where('game_id', $game_id);
        $this->db->where('marker != ', $marker);
        $query = $this->db->get();
        $res = $query->result_array();

        $turnRes = isset($res[0]['marker']) ? $res[0]['marker'] : null;

        $data = array(
            'map' => $map,
            'whose_turn' => $turnRes
        );
        $this->db->where('game_id', $game_id);
        $this->db->update('games', $data);

        return $turnRes;
    }


    /**
     * Определяет чей ход
     *
     * @return mixed
     */
    public function whoseTurn()
    {
        $this->load->database();
        $game_id = $_GET['game_id'];

        $this->db->select('whose_turn');
        $this->db->from('games');
        $this->db->where('game_id', $game_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $turn = $result[0]['whose_turn'];

        return $turn;
    }
}
