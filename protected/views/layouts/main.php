<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="en" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="КРЕДДИ - ты всегда при деньгах. Как просто получить и вернуть заём? Простая и удобная услуга займов. Сколько взял – столько вернул!" />
    <meta name="keywords" content="" />
    <meta name="author" content="деньги, наличные, электронные деньги, срочно нужны, где взять, взаймы, займу, быстрые, в займы, займ, заём, займы, микрофинансовая организация, кредит, долг, вдолг, потребительские, денежный, частный, беспроцентный, ссуда, за час, кредитование, без справок, доход, срочный, экспресс, проценты, до зарплаты, неотложные, по паспорту, под расписку, выгодный, кредитные карты, кредитные системы, кредитные организации, кредитные истории, занять, краткосрочные, физическим лицам"/>

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/960gs.css" />

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />



    <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/static/img/favicon.ico" />

    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/static/css/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/static/css/style.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/static/css/reset.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/static/css/payment.css" type="text/css" />

    <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>

    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/tab.js" charset="utf-8"></script>

	<!--script type="text/javascript" src="./static/js/collapse.js" charset="utf-8">
	</script-->
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/modal.js" charset="utf-8">
	</script>
	<!--script type="text/javascript" src="./static/js/tooltip.js" charset="utf-8">
	</script>
	<script type="text/javascript" src="./static/js/mask.js" charset="utf-8">
	</script>
	<script type="text/javascript" src="./static/js/alerts.js" charset="utf-8">
	</script>
	<script type="text/javascript" src="./static/js/script.js?v=2" charset="utf-8">
	</script-->

    <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/static/img/favicon.ico" />
</head>
<!-- ClickTale Bottom part -->
<!--div id="ClickTaleDiv" style="display: none;"></div-->
<!--script type="text/javascript">
    if(document.location.protocol!='https:')
        document.write(unescape("%3Cscript%20src='http://s.clicktale.net/WRe0.js'%20type='text/javascript'%3E%3C/script%3E"));
</script-->
<!--script type="text/javascript">
    if(typeof ClickTale=='function') ClickTale(7143,1,"www08");
</script-->
<!-- ClickTale end of Bottom part -->

<body class="home" data-spy="scroll">

<!-- ClickTale Top part -->
<script type="text/javascript">
    var WRInitTime=(new Date()).getTime();
</script>
<!-- ClickTale end of Top part -->

<nav class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container1">

            <!-- Special image
            <div class="new-year-left" style="margin: 5px 0px -145px -120px; float: left; background: url('<php echo Yii::app()->request->baseUrl; ?>/static/img/lenta9may.png') no-repeat; height: 140px; width: 112px"></div>
            -->

            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"></a> <a href="<?php echo Yii::app()->request->baseUrl; ?>/" class="brand"><img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/logo-slogan.png" alt="Kreddy"></a>

            <span class="hotline pull-right"><small>Горячая линия</small> 8 (800) 555-75-78 <small>(бесплатно по России)</small></span>
        </div>
    </div>
</nav>

<div class="page-divider1"></div>
<br/>

<?php echo $content; ?>

<br/>
<div class="page-divider1"></div>

<article class="container1">
    <section class="row">
        <h2>Узнай больше о нас!</h2>

        <p class="intro">Если возникнут вопросы, позвони нам, или <a href="mailto:info@kreddy.ru">напиши</a></p>

        <div class="about tabbable">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#zero" data-toggle="tab">Срочно нужны деньги?</a></li>

                <li><a href="#first" data-toggle="tab">Преимущества Kreddy</a></li>

                <li><a href="#second" data-toggle="tab">Как оплатить?</a></li>

                <li><a href="#third" data-toggle="tab">Как это работает?</a></li>

                <li><a href="#fourth" data-toggle="tab">Повторный займ</a></li>

                <li><a href="#fifth" data-toggle="tab">SMS-инфо</a></li>

				<li><a href="#pay" data-toggle="tab">Оплатить</a></li>
            </ul>

            <div class="tab-content">
                <div id="zero" class="tab-pane active">
                    <a href="#" id="case"></a>
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/case-a.jpg" alt="asd" width="960" />
                </div>

                <div id="first" class="tab-pane">
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/case-b.jpg" alt="asd" width="960" />
                </div>

                <div id="second" class="tab-pane">
                    <a href="?r=site/page&view=payments"><img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/case-j.jpg" alt="инструкция по оплате через киви или элекснет" class="pay_list_href"></a><a href="?r=site/page&view=cc"><img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/pay_cc/cc.png" alt="инструкция по оплате вашей банковской картой" class="pay_list_href" style="margin:20px;" width="90"></a>
                </div>

                <div id="third" class="tab-pane">
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/case-f.jpg" alt="asd" width="960" />
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/case-g.jpg" alt="asd" width="960" />
                </div>

                <div id="fourth" class="tab-pane">
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/case-h.png" alt="asd" width="800" />
                </div>

                <div id="fifth" class="tab-pane">
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/case-k.jpg" alt="asd" width="960" />
                </div>

				<div id="pay" class="tab-pane">
					<a href="https://pay.kreddy.ru/" target="_blank"><img src="<?php echo Yii::app()->request->baseUrl; ?>static/img/pay_cc/cc.png" alt="Оплата банковской картой" width="100" /></a>
					Оплата банковской картой
				</div>
            </div>
        </div>
    </section>
</article>

<div class="page-divider"></div>

<article class="container1">
    <section class="row">
        <footer>
            <p>
                <a data-toggle="modal" href="#svid">Свидетельство</a> &middot;
                <a data-toggle="modal" href="#rules">Правила займа</a> &middot;
                <a data-toggle="modal" href="#arch_rules">Архив правил займа</a> &middot;
                <a data-toggle="modal" href="#offer">Оферта</a> &middot;
                <a data-toggle="modal" href="#offer_mobile">Оферта на мобильный займ</a> &middot;
                <a data-toggle="modal" href="#contacts">Контакты</a>
            </p>

            <p>&copy; 2012  ООО "Финансовые Решения"</p>
        </footer>
    </section>
</article><!-- /container -->
<script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-30199735-1']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

</script>
<div class="modal hide fade" id="svid">
    <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h3>Свидетельство о внесении сведений в реестр микрофинансовых организаций</h3>
    </div>
    <div class="modal-body">
        <p><img src="<?php echo Yii::app()->request->baseUrl; ?>/static/docs/svid.jpg" alt="Свидетельство"/></p>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal">Закрыть</a>
    </div>
</div>

<div class="modal hide fade" id="privacy">
    <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h3>Условия обслуживания и передачи информации</h3>
    </div>
    <div class="modal-body">
        <p>Заполняя и отправляя в адрес ООО «Финансовые Решения» (далее – Общество) данную форму анкеты и/или форму анкеты, заполненную мною дистанционным способом, я подтверждаю правильность указанных мною персональные данных, принадлежащих лично мне, а так же выражаю свое согласие на обработку (в том числе сбор, систематизацию, проверку, уточнение, изменение, обновление, использование, распространение (в том числе передачу третьим лицам), обезличивание, блокирование, уничтожение персональных данных) ООО «Финансовые Решения», место нахождения: Москва, Гончарная наб. д.1 стр.4, своих  персональных данных, содержащихся в настоящей Анкете или переданных мною Обществу дистанционным способом. Персональные данные подлежат обработке (в том числе с использованием средств автоматизации) в целях принятия решения о предоставлении микрозайма, заключения, изменения, расторжения, дополнения, а также исполнения договоров микрозайма, дополнительных соглашений, заключенных или заключаемых впоследствии мною с ООО «Финансовые Решения». Настоящее согласие действует до момента достижения цели обработки персональных данных. Отзыв согласия на обработку персональных данных производится путем направления соответствующего письменного заявления  Обществу по почте. Так же выражаю свое согласие на информирование меня Обществом о размерах микрозайма, полной сумме, подлежащей выплате, информации по продуктам или рекламной информации Общества по телефону, электронной почте, SMS – сообщениями.</p>
        <p>Направляя в ООО «Финансовые Решения» данную Анкету/или форму анкеты, заполненную мною дистанционным способом выражаю свое согласие на получение и передачу ООО «Финансовые Решения» (Общество) информации, предусмотренной Федеральным законом № 218 от 30.12.2004  "О кредитных историях", о своей кредитной истории в соответствующее бюро кредитных историй (Бюро кредитных историй определяет Общество по своему усмотрению). Список бюро указан на сайте Общества <a href="http://kreddy.ru/" target="_blank">www.kreddy.ru</a>, а также с тем, что в случае неисполнения, ненадлежащего исполнения и/или задержки исполнения мною своих обязательств по договорам микрозайма, заключенных с Обществом, Общество вправе раскрыть информацию об этом любым лицам (в т.ч. неопределенному кругу лиц) и любым способом (в т.ч. путем опубликования в средствах массовой информации).</p>
        <p>Направляя/подписывая в ООО «Финансовые Решения» данную форму Анкеты или анкету, заполненную мною дистанционным способом, подтверждаю, что ознакомлен с правилами предоставления микрозайма, со всеми условиями предоставления микрозайма. Также подтверждаю, что номер мобильного телефона, указанный в анкете, принадлежит лично мне. Ответственность за неправомерное использование номера мобильного телефона лежит на мне.</p>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal">Закрыть</a>
    </div>
</div>

<div class="modal modal_big hide fade" id="contacts">
    <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h3>Адрес отделения Kreddy</h3>
    </div>
    <div class="modal-body">
        <p>Торговый Комплекс «Город»</p>
        <p><img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/transport_scheme_1_2.jpg" alt="Карта"/></p>
        <p><img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/transport_scheme_2_1.jpg" alt="Карта"/></p>
        <p><img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/transport_scheme_3_1.jpg" alt="Карта"/></p>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal">Закрыть</a>
    </div>
</div>

<div class="modal hide fade" id="myModal">
    <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h3>Адрес отделения Kreddy</h3>
    </div>
    <div class="modal-body">
        <p>Торговый Комплекс «Город»</p>
        <p>Москва, Рязанский проспект, дом 2, корпус 2. (рядом со входом в АШАН)</p>
        <p>Инструкция по оплате счёта - <a href="http://ishopnew.qiwi.ru/files/qiwi_instruction.html">http://ishopnew.qiwi.ru/files/qiwi_instruction.html</a></p>
        <p>Схема расположения ближайшего терминала QIWI</p>
        <p><img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/map.png" alt="Карта"/></p>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal">Закрыть</a>
    </div>
</div>

<div class="modal modal hide fade" id="rules">
    <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h3>
            Правила предоставления микрозайма
            <br />
            ООО «Финансовые Решения»
        </h3>
    </div>
    <div class="modal-body">
        <p><img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/last_terms_1.jpg?v=1" alt="1"/></p>
        <p><img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/last_terms_2.jpg?v=1" alt="2"/></p>
        <p><img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/last_terms_3.jpg?v=1" alt="3"/></p>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal">Закрыть</a>
    </div>
</div>

<div class="modal hide fade" id="arch_rules">
    <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h3>
            Архив правил предоставления микрозайма
            <br />
            ООО «Финансовые Решения»
        </h3>
    </div>
    <div class="modal-body">
        <p><h3>от 01.07.2012</h3></p>
        <p>Утверждено Приказом Генерального директора
            <br />
            №3/р от 01 июля 2012 г.
            <br />
            ООО «Финансовые Решения» Валаевой О.Г.
            <br />
            <br />
            Правила предоставления микрозайма ООО «Финансовые Решения»
        </p>
        <ol>
            <li>
                <p>ООО «Финансовые решения» (Займодавец) предоставляет микрозаймы физическим лицам - гражданам РФ (Заемщик), в возрасте от 20 до 60 лет, постоянно зарегистрированным в Москве или Московской области.</p>
            </li>
            <li>
                <p>Микрозаймы предоставляются без поручителей, залога и иного обеспечения.</p>
            </li>
            <li>
                <p>Микрозаймы ООО «Финансовые Решения» предоставляются заемщикам для использования в личных целях, в валюте Российской Федерации на основании договора микрозайма.</p>
            </li>
            <li>
                <p>Для получения микрозайма необходимо:</p>
            </li>
            <ul>
                <li>
                    <p>предоставить оригинал общегражданского паспорта Российской Федерации;</p>
                </li>
                <li>
                    <p>предоставить второй документ, удостоверяющий личность: заграничный паспорт, водительское удостоверение, пенсионное удостоверение, военный билет, свидетельство ИНН, страховое свидетельство государственного пенсионного страхования;</p>
                </li>
                <li>
                    <p>заполнить анкету для получения микрозайма.</p>
                </li>
            </ul>
            <li>
                <p>При оформлении документов сотрудник ООО «Финансовые Решения» производит фотографирование лица заемщика, а также сканирование (копирование) предоставленных документов.</p>
            </li>
            <li>
                <p>Рассмотрение анкеты и принятие решения о предоставлении микрозайма (или решения об отказе в предоставлении микрозайма), как правило, составляет не более 20 минут с момента обращения.</p>
            </li>
            <li>
                <p>При принятии положительного решения о предоставлении микрозайма заключается договор микрозайма. Договор микрозайма составляется в простой письменной форме, в двух одинаковых экземплярах и подписывается Заемщиком и Займодавцем, при этом каждой из сторон остается по одному оригинальному экземпляру договора.</p>
            </li>
            <li>
                <p>Микрозаем предоставляется путем перечисления денежных средств на карту Заемщика, эмитированную банком и выдаваемую ООО «Финансовые Решения», в день обращения.</p>
            </li>
            <li>
                <p>Днем передачи денежных средств признается дата зачисления суммы микрозайма на карту Заемщика.</p>
            </li>
            <li>
                <p>Сотрудник ООО «Финансовые Решения», в соответствии с действующим законодательством, вправе отказать в предоставлении микрозайма.</p>
            </li>
            <li>
                <p>ООО «Финансовые Решения» предоставляет микрозаем в сумме и на срок, в зависимости от продукта:</p>
                <ul>
                    <li>
                        <p>продукт «7 дней»: 6000 рублей на срок 7 (семь) дней;</p>
                    </li>
                    <li>
                        <p>продукт «14 дней»: 10000 рублей на срок 14 (четырнадцать) дней;</p>
                    </li>
                    <li>
                        <p>продукт «3»: 3000 рублей на срок 7 (семь) дней;</p>
                    </li>
                    <li>
                        <p>продукт «4»: 4000 рублей на срок 7 (семь) дней;</p>
                    </li>
                    <li>
                        <p>продукт «5»: 5000 рублей на срок 7 (семь) дней.</p>
                    </li>
                </ul>
            </li>
            <li>
                <p>ООО «Финансовые Решения» предоставляет услугу, подключившись к которой Заемщик получает возможность неоднократно получать микрозаем в течение срока подключения, без каких-либо дополнительных платежей. </p>
                <p>Вид продукта и количество повторных получений микрозайма в течение срока подключения:</p>
                <p>А) Подключение сроком на 2 (два) месяца стоимостью 1500 (одна тысяча пятьсот) рублей (Сумма за подключение).</p>
                <ul>
                    <li>
                        <p>продукт «7 дней»: не более 4 раз;</p>
                    </li>
                    <li>
                        <p>продукт «14 дней»: не более 2 раз.</p>
                    </li>
                </ul>
                <p>Б) Подключение сроком на 1 (один) месяц стоимостью 600 (шестьсот) рублей по продукту:</p>
                <ul>
                    <li>
                        <p>продукт «4» не более 2 раз</p>
                    </li>
                </ul>
                <p>В) Подключение сроком на 1 (один) месяц стоимостью 300 (триста) рублей по продуктам:</p>
                <ul>
                    <li>
                        <p> продукт «3»: не более 2 раз;</p>
                    </li>
                    <li>
                        <p>продукт «5» не более 2 раз.</p>
                    </li>
                </ul>
            </li>
            <li>
                <p>Сумма за подключение уплачивается Заемщиком на расчетный счет Займодавца единовременным платежом в день подписания договора микрозайма до выдачи суммы микрозайма. </p>
            </li>
            <li>
                <p>Выдача повторного микрозайма осуществляется только при условии уплаты Заемщиком Суммы за подключение, а так же при условии соблюдения и выполнения всех условий настоящих правил и договора микрозайма. Повторные выдачи микрозайма оформляются путем заключения дополнительных соглашений к договору микрозайма. </p>
            </li>
            <li>
                <p>В последний день срока пользования микрозаймом, установленного договором микрозайма, заемщик должен возвратить денежные средства в размере Суммы микрозайма.</p>
            </li>
            <li>
                <p>Заемщик имеет право погасить микрозаем досрочно.</p>
            </li>
            <li>
                <p>Возврат Суммы микрозайма осуществляется путем внесения денежных средств на расчетный счет ООО «Финансовые Решения» любым из способов: через терминалы приема платежей, электронные платежные системы, безналичным перечислением или внесением наличных денег в кассу отделения Банка ЗАО «Новый Символ» </p>
            </li>
            <li>
                <p>Обязанность Заемщика по возврату Суммы микрозайма считается выполненной в день поступления денежных средств в сумме, составляющей Сумму микрозайма, на расчетный счет ООО «Финансовые Решения».</p>
            </li>
            <li>
                <p>При нарушении Заемщиком сроков возврата Суммы микрозайма Займодавец вправе потребовать уплаты Заемщиком пени (неустойки) в размере 100 (сто) рублей, при подключении, указанном в пункте 12А и 12Б , или 30 (тридцать) рублей, при подключении, указанном в пункте 12 В, за каждый день просрочки, единовременного штрафа в размере 3000 (трех) тысяч рублей на седьмой день просрочки, только при подключении согласно пункту 12А и 12Б, а так же обратиться в коллекторское агентство для взыскания задолженности с Заемщика в досудебном порядке или обратиться в суд за защитой нарушенных прав и своих законных интересов.</p>
            </li>
            <li>
                <p>Заемщик имеет возможность не подключаться к Услуге ООО «Финансовые Решения» и получить микрозаем, уплатив Сумму за пользование в день заключения договора микрозайма:</p>
                <ul>
                    <li>продукты «7 дней», «14 дней» - Сумма за пользование микрозаймом составляет 1200 рублей.</li>
                    <li>продукт «4» - Сумма за пользование микрозаймом составляет 600 рублей;</li>
                    <li>продукты «3» и «5»- Сумма за пользование микрозаймом составляет 300 рублей.</li>
                </ul>
            </li>
            <li>
                <p>ООО «Финансовые Решения» имеет право информировать Заемщика о размерах микрозайма, полной сумме, подлежащей выплате Заемщиком, и иной информации, касающейся услуги по предоставлению микрозаймов ООО «Финансовые Решения», по телефону, электронной почте, SMS - сообщениями.</p>
            </li>
            <li>
                <p>Займодавец вправе, с согласия Заемщика, обрабатывать, получать, передавать, хранить и уничтожать персональные данные и информацию, полученную от Заемщика, а так же из любых других источников, отправлять информацию Заемщику путем отправки SMS - сообщений. Займодавец вправе использовать персональные данные и информацию по Заемщику, в целях обеспечения исполнения обязательств по Договору микрозайма, а так же, с согласия Заемщика, вправе получать и передавать данные и информацию по Заемщику, третьим лицам, в том числе в коллекторские агентства и Бюро кредитных историй. </p>
            </li>
        </ol>
        <p><h3>от 21.02.2013</h3></p>
        <p><img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/arch_terms_1.jpg?v=1" alt="1"/></p>
        <p><img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/arch_terms_2.jpg?v=1" alt="2"/></p>
        <p><img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/arch_terms_3.jpg?v=1" alt="3"/></p>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal">Закрыть</a>
    </div>
</div>

<div class="modal modal hide fade" id="offer">
    <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h3>
            Оферта
        </h3>
    </div>
    <div class="modal-body">
        <p><img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/offer/001.jpg" alt="1"/></p>
        <p><img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/offer/002.jpg" alt="2"/></p>
        <p><img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/offer/003.jpg" alt="3"/></p>
        <p><img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/offer/004.jpg" alt="4"/></p>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal">Закрыть</a>
    </div>
</div>

<div class="modal modal hide fade" id="offer_mobile">
    <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h3>
            Оферта на мобильный займ
        </h3>
    </div>
    <div class="modal-body">
        <p><img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/offer_mobile_1.jpg?v=1" alt="1"/></p>
        <p><img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/offer_mobile_2.jpg?v=1" alt="2"/></p>
        <p><img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/offer_mobile_3.jpg?v=1" alt="3"/></p>
        <p><img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/offer_mobile_4.jpg?v=1" alt="4"/></p>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal">Закрыть</a>
    </div>
</div>

<script type="text/javascript">
    var yaParams = {/*Здесь параметры визита*/};
</script>

<!--script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter21390544 = new Ya.Metrika({id:21390544,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    trackHash:true,params:window.yaParams||{ }});
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script-->
<!--noscript><div><img src="//mc.yandex.ru/watch/21390544" style="position:absolute; left:-9999px;" alt="" /></div></noscript-->

<!-- BEGIN JIVOSITE CODE {literal} -->
<!--script type='text/javascript'>
    (function(){ var widget_id = '65726';
        var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);})();</script>
<!-- {/literal} END JIVOSITE CODE -->

</body>
</html>