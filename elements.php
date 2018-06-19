<?php

include_once './IApiUsable.php';
include_once './InternalResponse.php';

//Enums
include_once './Model/Enums/userType.php';
include_once './Model/Enums/itemType.php';
include_once './Model/Enums/orderStatus.php';
include_once './Model/Enums/errorType.php';

//Entidades
include_once './Model/ICodeGenerator.php';
include_once './Model/entity.php';
include_once './Model/requestResponse.php';
include_once './Model/order.php';
include_once './Model/user.php';

//Repository
include_once './Repository/AccesoDatos.php';
include_once './Repository/userRepository.php';
include_once './Repository/tokenRepository.php';
include_once './Repository/orderRepository.php';

//Api
include_once './Api/userApi.php';
include_once './Api/tokenApi.php';
include_once './Api/orderApi.php';

include_once './Api/Token/token.php';
include_once './Api/Token/token.php';

include_once './Middleware/loginMiddleware.php';

require './vendor/autoload.php';
