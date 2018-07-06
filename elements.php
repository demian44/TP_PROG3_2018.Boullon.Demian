<?php

const PROYECT_NAME = "/TP_PROG3_2018.Boullon.Demian/";

include_once './IApiUsable.php';
include_once './InternalResponse.php';

//Enums
include_once './Model/Enums/userType.php';
include_once './Model/Enums/itemType.php';
include_once './Model/Enums/orderStatus.php';
include_once './Model/Enums/errorType.php';
include_once './Model/Enums/mesaType.php';

//Models
include_once './Model/ICodeGenerator.php';
include_once './Model/entity.php';
include_once './Model/foto.php';
include_once './Model/item.php';
include_once './Model/orderItem.php';
include_once './Model/requestResponse.php';
include_once './Model/order.php';
include_once './Model/mesa.php';
include_once './Model/user.php';

//Repository
include_once './Repository/AccesoDatos.php';
include_once './Repository/userRepository.php';
include_once './Repository/tokenRepository.php';
include_once './Repository/orderRepository.php';
include_once './Repository/mesaRepository.php';

//Api
include_once './Api/userApi.php';
include_once './Api/tokenApi.php';
include_once './Api/mesaApi.php';
include_once './Api/orderApi.php';

include_once './Api/Token/token.php';
include_once './Api/Token/token.php';

include_once './Middleware/orderMiddleware.php';
include_once './Middleware/userMiddleware.php';
include_once './Middleware/loginMiddleware.php';

require './vendor/autoload.php';
