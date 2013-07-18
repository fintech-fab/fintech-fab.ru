<?php
/* рассмотрим здесь "параметризированный" виджет*/
class TopPageWidget extends CWidget {

    public function run() {
        // передаем данные в представление виджета
        $this->render('toppage');
    }
}