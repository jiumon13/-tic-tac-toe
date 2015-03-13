<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Field extends CI_Controller {

    public function index()
    {
        $this->load->model('field_model');
        $this->field_model->create_game();
        $this->field_model->insert_user();

        $data['marker'] = $this->field_model->getMarker();
        $data['user_id'] = $_GET['user_id'];
        $data['game_id'] = $_GET['game_id'];
        $data['map'] = $this->field_model->getMapJSON();
        $data['whose_turn'] = $this->field_model->whoseTurn();

        $this->load->view('main', $data);
    }

    /**
     * Получение данных карты JSON массивом
     */
    public function getMapJSON()
    {
        $this->load->model('field_model');
        echo $this->field_model->getMapJSON();
    }

    /**
     *  Вызов метода модели, отвечающий за ход игрока
     */
    public function move()
    {
        $this->load->model('field_model');
        echo $this->field_model->move();
    }

    public function whoseTurn()
    {
        $this->load->model('field_model');
        echo $this->field_model->whoseTurn();
    }
}
