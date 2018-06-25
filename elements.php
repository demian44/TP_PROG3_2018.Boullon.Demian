<?php

include_once './IApiUsable.php';

//Enums
include_once './Model/Enums/userType.php';
include_once './Model/Enums/errorType.php';

//Entidades
include_once './Model/entity.php';
include_once './Model/requestResponse.php';
include_once './Model/foto.php';
include_once './Model/media.php';
include_once './Model/user.php';

//Repository
include_once './Repository/AccesoDatos.php';
include_once './Repository/userRepository.php';
include_once './Repository/tokenRepository.php';
include_once './Repository/mediaRepository.php';

//Api
include_once './Api/userApi.php';
include_once './Api/tokenApi.php';
include_once './Api/mediaApi.php';

//Token
include_once './Api/Token/token.php';
include_once './Api/Token/token.php';

//Middleware
include_once './Middleware/loginMiddleware.php';
include_once './Middleware/mediaMiddleware.php';
include_once './Middleware/userMiddleware.php';

require './vendor/autoload.php';
