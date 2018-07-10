<?php

class OrderRepository
{
    /**
     * Solo para socios.
     */
    public static function GetAll(string $imgUrl, int $category)
    {

        $arrayOrders = [];
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $mysqlQuery = Self::MakeGetQueryByCategory($category, ORDER_STATUS::NEWORDER); //Filtra las ordenes dependiendo de las categorias.
            $consulta = $objetoAccesoDato->RetornarConsulta($mysqlQuery);
            $row = $consulta->execute();
            $lastOrderId = -1;

            foreach ($consulta->fetchAll() as $row) {

                if ($lastOrderId != $row["order_id"]) {
                    $lastOrderId = $row["order_id"];
                    $order = new Order($row["client_name"], $row["code"], $row["mesa_id"]);
                    $order->SetStatus($row["status"]);
                    $order->SetEstimateTime($row["estimate_time"]);
                    $order->SetOrderedTime($row["ordered_time"]);
                    $order->SetFoto($imgUrl . $row["foto"]);
                    array_push($arrayOrders, $order);
                }

                $orderItem = new OrderItem($row["order_item_id"]);
                $orderItem->SetCant($row["cant"]);

                $orderItem->SetName($row["name"]);
                $orderItem->SetSector($row["sector"]);
                // var_dump($arrayOrders[count($arrayOrders) - 1]);
                $arrayOrderItem = $arrayOrders[count($arrayOrders) - 1]->GetItems();

                $array = $arrayOrders[count($arrayOrders) - 1]->GetItems();
                array_push($arrayOrderItem, $orderItem);

                $arrayOrders[count($arrayOrders) - 1]->SetItems($arrayOrderItem);

            }
            $arrayOrders = Order::ToJsonArray($arrayOrders);
            if (count($arrayOrders) > 0) {
                $return = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, $arrayOrders);
            } else {
                $return = new ApiResponse(REQUEST_ERROR_TYPE::NODATA, "Sin elementos");
            }

        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $return;
    }

    private static function GetCocinerosFromOrderId(int $id): array
    {
        $cocineros = [];
        if ($id >= 0) {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $mysqlQuery = 'SELECT users.id,users.name FROM order_item
            INNER JOIN users ON order_item.employee_take_it = users.id
            WHERE order_item.order_id = ' . $id . '
            GROUP BY users.id
            AND users.category = 3';

            $consulta = $objetoAccesoDato->RetornarConsulta($mysqlQuery);
            $consulta->execute();
            foreach ($consulta->fetchAll() as $row) {
                $cocinero["nombre"] = $row["name"];
                $cocinero["id"] = $row["id"];
                array_push($cocineros, $cocinero);
            }
        }
        return $cocineros;
    }

    public static function GetPendings(string $imgUrl, string $user)
    {
        $arrayOrders = [];
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $mysqlQuery = 'SELECT  order_item.id as order_item_id, order_item.cant, order_id,client_name,
                code, mesa_id, orders.status, orders.estimate_time, ordered_time, foto, items.name,items.sector
                FROM  orders
                INNER JOIN order_item
                ON orders.id = order_item.order_id
                INNER JOIN items
                ON items.id = order_item.item_id
                INNER JOIN users ON users.id = order_item.employee_take_it
                WHERE users.user = :user
                AND orders.active = 1
                AND order_item.active = 1
                AND order_item.status = :status';
            $consulta = $objetoAccesoDato->RetornarConsulta($mysqlQuery);
            $consulta->bindValue(':user', $user, PDO::PARAM_STR);
            $consulta->bindValue(':status', ORDER_STATUS::MAKING, PDO::PARAM_INT);
            $row = $consulta->execute();
            $lastOrderId = -1;
            foreach ($consulta->fetchAll() as $row) {
                if ($lastOrderId != $row["order_id"]) {
                    $lastOrderId = $row["order_id"];
                    $order = new Order($row["client_name"], $row["code"], $row["mesa_id"]);
                    $order->SetStatus($row["status"]);
                    $order->SetEstimateTime($row["estimate_time"]);
                    $order->SetOrderedTime($row["ordered_time"]);
                    $order->SetFoto($imgUrl . $row["foto"]);
                    array_push($arrayOrders, $order);
                }
                $orderItem = new OrderItem($row["order_item_id"]);
                $orderItem->SetCant($row["cant"]);
                $orderItem->SetName($row["name"]);
                $orderItem->SetSector($row["sector"]);
                $arrayOrderItem = $arrayOrders[count($arrayOrders) - 1]->GetItems();
                $array = $arrayOrders[count($arrayOrders) - 1]->GetItems();
                array_push($arrayOrderItem, $orderItem);
                $arrayOrders[count($arrayOrders) - 1]->SetItems($arrayOrderItem);
            }
            $arrayOrders = Order::ToJsonArray($arrayOrders);
            if (count($arrayOrders) > 0) {
                $return = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, $arrayOrders);
            } else {
                $return = new ApiResponse(REQUEST_ERROR_TYPE::NODATA, "Sin elementos");
            }
        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }
        return $return;

    }

    public static function GetByOrderItemId(int $orderItemId): Order
    {
        $order = null;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objetoAccesoDato->RetornarConsulta('SELECT orders.* FROM orders
            INNER JOIN order_item ON order_item.order_id = orders.id
            WHERE order_item.id = :orderItemId');
        $consulta->execute(array(':orderItemId' => $orderItemId));
        $row = $consulta->fetch();
        if ($row) {

            $order = new Order($row["client_name"], $row["code"],$row["mesa_id"]);
            $order->SetStatus($row["status"]);
            $order->SetEstimateTime($row["estimate_time"]);
            $order->SetOrderedTime($row["ordered_time"]);
            $order->SetFoto($row["delivered_time"]);
            $order->SetId($row["id"]);
        }
        return $order;
    }

    public static function GetStateOrder(string $orderCode, string $mesaCode)
    {

        $order = null;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objetoAccesoDato->RetornarConsulta('SELECT orders.estimate_time FROM orders
        INNER JOIN mesas ON orders.mesa_id = mesas.id
        WHERE orders.code = :orderCode AND mesas.code = :mesaCode');
        $consulta->bindValue(':orderCode', $orderCode, PDO::PARAM_STR);
        $consulta->bindValue(':mesaCode', $mesaCode, PDO::PARAM_STR);
        $consulta->execute();
        $row = $consulta->fetch();
        if ($row) {
            date_default_timezone_set('America/Argentina/Buenos_Aires');
            $fecha = date('Y/m/d H:i');

            if (strtotime($fecha) < strtotime($row["estimate_time"])) {
                $diferencia = strtotime($row["estimate_time"]) - strtotime($fecha);
                $diferencia = ($diferencia / 60); //Saco la diferencia en minutos
                $return = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, $diferencia . " minutos.");
            } else { //EN este caso es cuando no está seteado el tiempo estimado o cuando se paso
                $return = new ApiResponse(REQUEST_ERROR_TYPE::DEMORADO, "En instantes...");
            }
        } else {
            $return = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, "No coinciden los codigos");
        }

        return $return;

    }

    public static function MakeGetQueryByCategory(int $category, int $status)
    {
        $mysqlQuery = null;
        if (is_int($category)) {

            $mysqlQuery = 'SELECT  order_item.id as order_item_id, order_item.cant, order_id,client_name,
            code, mesa_id, orders.status, orders.estimate_time, ordered_time, foto, items.name,items.sector
            FROM  orders
            INNER JOIN order_item
            ON orders.id = order_item.order_id
            INNER JOIN items
            ON items.id = order_item.item_id WHERE orders.active = 1  AND order_item.active = 1 ';

            switch ($category) {
                case USER_CATEGORY::MOZO:
                    $mysqlQuery .= " AND orders.status = " . ORDER_STATUS::READY;
                    break;
                case USER_CATEGORY::CERBECERO:
                    $mysqlQuery .= " AND order_item.status = " . $status .
                    " AND items.sector = " . USER_CATEGORY::CERBECERO;
                    break;
                case USER_CATEGORY::COCINERO:
                    $mysqlQuery .= " AND order_item.status = " . $status .
                    " AND items.sector = " . USER_CATEGORY::COCINERO;
                    break;
                case USER_CATEGORY::COCINERO_CANDY:
                    $mysqlQuery .= " AND order_item.status = " . $status .
                    " AND items.sector = " . USER_CATEGORY::COCINERO_CANDY;
                    break;
                case USER_CATEGORY::COCINERO_CANDY:
                    $mysqlQuery .= " AND order_item.status = " . $status .
                    " AND items.sector = " . USER_CATEGORY::COCINERO_CANDY;
                    break;
                case USER_CATEGORY::BARTENDER:
                    $mysqlQuery .= " AND order_item.status = " . $status .
                    " AND items.sector = " . USER_CATEGORY::BARTENDER;
                    break;
            }
        }

        return $mysqlQuery;
    }

    /**
     * Agregar Throw.
     */
    public static function InsertOrder(Order $order, int $mozoId)
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta('INSERT INTO orders '
                . '(client_name,code,ordered_time,mesa_id,foto,mozo_id) '
                . 'VALUES(:client_name,:code,:ordered_time,:mesa_id,:foto,:mozo_id)');

            $consulta->bindValue(':client_name', $order->GetCliente(), PDO::PARAM_STR);
            $consulta->bindValue(':code', $order->GetCode(), PDO::PARAM_STR);
            $consulta->bindValue(':ordered_time', $order->GetOrderedTime(), PDO::PARAM_STR);
            $consulta->bindValue(':mesa_id', $order->GetMesaId(), PDO::PARAM_INT);
            $consulta->bindValue(':foto', $order->GetFoto(), PDO::PARAM_INT);
            $consulta->bindValue(':mozo_id', $mozoId, PDO::PARAM_INT);

            if (!$consulta->execute()) { //Si no retorna 1 no guardó el elemento
                $response = new ApiResponse(REQUEST_ERROR_TYPE::DATABASE, 'Error al guardar la orden en la base de datos.');
            } else {

                $consulta = $objetoAccesoDato->RetornarConsulta('SELECT MAX(id) FROM orders');
                $consulta->execute();
                $row = $consulta->fetch();
                Self::SaveOrderItems($order, $row[0]);
                $succesResponse["code"] = $order->GetCode();
                $succesResponse["mesaCode"] = $order->GetMesaCode();
                $response = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, $succesResponse);
            }

            MesaRepository::SetStatus($order->GetMesaId(), MESA_STATUS::CON_CLIENTE_ESPERANDO_PEDIDO);
        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $response;
    }

    /**
     * Agregar Throw.
     */
    public static function TakeOrder(array $orderItems, int $id, int $category): ApiResponse
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            date_default_timezone_set('America/Argentina/Buenos_Aires');
            $fecha = date('Y/m/d H:i');
            $totalMinutes = 0;
            foreach ($orderItems as $key => $orderItem) {
                $totalMinutes += $orderItem->stimatedTime;

                $consulta = $objetoAccesoDato->RetornarConsulta('UPDATE order_item SET status= '
                    . ":status, employee_take_it = :employeeId, estimated_time =:time WHERE id=:id");

                $consulta->bindValue(':employeeId', $id, PDO::PARAM_INT);
                $consulta->bindValue(':time', $orderItem->stimatedTime, PDO::PARAM_INT);
                $consulta->bindValue(':status', ORDER_STATUS::MAKING, PDO::PARAM_INT);
                $consulta->bindValue(':id', $orderItem->id, PDO::PARAM_INT);
                $consulta->execute();

            }
            $order = Self::GetByOrderItemId($orderItems[0]->id);
            $stimatedDate = strtotime('+' . $totalMinutes . ' minute', strtotime($fecha));
            $nuevafecha = date('Y/m/d H:i', $stimatedDate);

            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT * FROM orders WHERE id= :id');
            $consulta->bindValue(':id', $order->GetId(), PDO::PARAM_INT);
            $consulta->execute();
            $row = $consulta->fetch();

            //En casa de que este cocinero tenga un tiempo estimado que exceda al del pedido general el valor del pedido general
            //será reemplazado.
            if (strtotime($row["estimate_time"]) < strtotime($nuevafecha)) {
                $consulta = $objetoAccesoDato->RetornarConsulta('UPDATE orders SET status = '
                    . ":status, estimate_time = :time WHERE id= :id");

                $consulta->bindValue(':id', $order->GetId(), PDO::PARAM_INT);
                $consulta->bindValue(':time', $nuevafecha, PDO::PARAM_STR);
                $consulta->bindValue(':status', ORDER_STATUS::MAKING, PDO::PARAM_INT);
                $consulta->execute();
            }
            $response = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, "Orden preparandose"); // $succesResponse);
        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $response;
    }
    /**
     * Agregar Throw.
     */
    public static function ResolvePending(array $orderItems): ApiResponse
    {
        try {

            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            date_default_timezone_set('America/Argentina/Buenos_Aires');
            $fecha = date('Y/m/d H:i');
            $totalMinutes = 0;
            foreach ($orderItems as $key => $orderItem) {

                $consulta = $objetoAccesoDato->RetornarConsulta('UPDATE order_item SET status= '
                    . ":status WHERE id=:id");

                $consulta->bindValue(':status', ORDER_STATUS::READY, PDO::PARAM_INT);
                $consulta->bindValue(':id', $orderItem->id, PDO::PARAM_INT);
                $consulta->execute();
            }
            $order = Self::GetByOrderItemId($orderItems[0]->id);

            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT * FROM order_item WHERE order_id= :order_id');
            $consulta->bindValue(':order_id', $order->GetId(), PDO::PARAM_INT);
            $consulta->execute();
            $readyOrder = true;
            foreach ($consulta->fetchAll() as $row) {
                if ($row["status"] == ORDER_STATUS::MAKING) {
                    $readyOrder = false;
                    break;
                }
            }

            if ($readyOrder) { // Si todos los items están listos se pone el pedido como listo.
                $consulta = $objetoAccesoDato->RetornarConsulta('UPDATE orders set status = ' .
                    ORDER_STATUS::READY . ' WHERE id= :id');
                $consulta->bindValue(':id', $order->GetId(), PDO::PARAM_INT);
                $consulta->execute();
                $response = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, "Items listos para servir (Pedido incompleto)"); // $succesResponse);
            } else {
                $response = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, "Items listos para servir"); // $succesResponse);
            }
        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $response;
    }

    /**
     * Agregar Throw.
     */
    public static function DeliverOder(int $id): ApiResponse
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            date_default_timezone_set('America/Argentina/Buenos_Aires');
            $fecha = date('Y/m/d H:i');

            $consulta = $objetoAccesoDato->RetornarConsulta('UPDATE orders set status = '
                . ORDER_STATUS::DELIVERED . ', delivered_time =  "' . $fecha . '" WHERE id= :id');
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->execute();

            $consulta = $objetoAccesoDato->RetornarConsulta('UPDATE order_item set status = ' . ORDER_STATUS::DELIVERED .
                ' WHERE order_id= :id');
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            $response = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, "Pedido entrgado."); // $succesResponse);

            $order = Self::GetByOrderItemId($id);
            MesaRepository::SetStatus($order->GetMesaId(),MESA_STATUS::CON_CLIENTES_COMIENDO);  

        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $response;
    }

    private static function SaveOrderItems(Order $order, int $orderId)
    {
        $items = $order->GetItems();
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        foreach ($items as $key => $item) {
            $mysqlQuery = ' INSERT INTO order_item (item_id,cant,order_id) '
            . 'VALUES( ' . $item->GetItemId() . ' , ' . $item->GetCant() . ' , ' . $orderId . ' );';
            $consulta = $objetoAccesoDato->RetornarConsulta($mysqlQuery);
            $consulta->execute();
        }
    }

    /**
     * Chequea si los ids cargados estan cargados en la base de items.
     **/
    public static function CheckItems(array $items)
    {
        $idsFaltantes = [];
        $idsEncontrados = [];

        $mysqlQuery = "SELECT id FROM items WHERE id in (";
        foreach ($items as $key => $item) {
            if ($key > 0) {
                $mysqlQuery .= ",";
            }
            $mysqlQuery .= " " . $item;
        }
        $mysqlQuery .= ");";

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta($mysqlQuery);
        $consulta->execute();
        $rows = $consulta->fetchAll();

        foreach ($rows as $row) {
            array_push($idsEncontrados, $row[0]);
        }

        foreach ($items as $key => $item) {
            if (array_search($item, $idsEncontrados) === false) {
                array_push($idsFaltantes, $item);
            }
        }

        return $idsFaltantes;
    }

    public static function CheckOrderItems(array $orderItems, int $category, string &$problem): bool
    {
        $idsFaltantes = [];
        $idsEncontrados = [];
        $return = false;

        $mysqlQuery = "SELECT order_item.id, items.sector FROM order_item" .
            " INNER JOIN items ON items.id = order_item.item_id WHERE order_item.id in ( ";
        foreach ($orderItems as $key => $orderItem) {
            if ($key > 0) {
                $mysqlQuery .= ",";
            }

            $mysqlQuery .= " " . $orderItem->id;
        }
        $mysqlQuery .= ") AND items.sector  = :category AND  order_item.status = 0";

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta($mysqlQuery);
        $consulta->bindValue(':category', $category, PDO::PARAM_INT);
        $consulta->execute();
        $rows = $consulta->fetchAll();

        if (count($rows) == count($orderItems)) {
            $return = true;
        } else {
            $problem = "Ids corresponden a elementos que no pertenecen a la categoría de este usuario, ya fueron tomados o no existe.";
        }

        return $return;
    }

    public static function ExistOrderItemsToResolve(array $orderItems, int $category, string &$problem): bool
    {
        $idsFaltantes = [];
        $idsEncontrados = [];
        $return = false;

        $mysqlQuery = "SELECT order_item.id, items.sector FROM order_item" .
            " INNER JOIN items ON items.id = order_item.item_id WHERE order_item.id in ( ";
        foreach ($orderItems as $key => $orderItem) {
            if ($key > 0) {
                $mysqlQuery .= ",";
            }

            $mysqlQuery .= " " . $orderItem->id;
        }
        $mysqlQuery .= ") AND items.sector  = :category AND  order_item.status = 1";

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta($mysqlQuery);
        $consulta->bindValue(':category', $category, PDO::PARAM_INT);
        $consulta->execute();
        $rows = $consulta->fetchAll();

        if (count($rows) == count($orderItems)) {
            $return = true;
        } else {
            $problem = "Ids corresponden a elementos que no pertenecen a la categoría de este usuario, ya fueron tomados o no existe.";
        }

        return $return;
    }

    public static function CheckCodes($code)
    {
        $return = false;
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT id FROM orders WHERE code = :code');
            $consulta->execute(array(':code' => $code));
            $row = $consulta->fetch();
            if ($row) {
                $return = true;
            }
        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $return;
    }

    public static function ModificarPedido($id, $cliente, $sexo, $cantante)
    {
    }

    public static function EliminarPedido($id)
    {
        // $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        // $consulta = $objetoAccesoDato->RetornarConsulta("DELETE FROM cds WHERE id = :id");

        // $consulta->bindValue(':id', $id, PDO::PARAM_INT);

        // return $consulta->execute();
    }

    public static function GetOrderInfo($id)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objetoAccesoDato->RetornarConsulta('SELECT orders.delivered_time,
        orders.ordered_time,SUM(items.precio) as total FROM  orders
        INNER JOIN order_item ON order_item.order_id = orders.id
        INNER JOIN items ON order_item.item_id = items.id
        GROUP BY orders.id
        ');
        $consulta->execute();

        $array = [];

        foreach ($consulta->fetchAll() as $row) {
            $statistics["horaFin"] = $row["delivered_time"];
            $statistics["horaInicio"] = $row["ordered_time"];
            $statistics["importeTotal"] = $row["total"];
            array_push($array, $statistics);
        }
    }

    public static function SetEvaluation(Statisctic $statistic)
    {

        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta('INSERT INTO order_evaluation
                (order_code,mesa_evaluation,mesa_code ,mozo_id,mozo_evaluation,
                restaurant_evaluation,comentario)
                values(:order_code,:mesa_evaluation,:mesa_code ,:mozo_id,:mozo_evaluation,
                :restaurant_evaluation,:comentario)');
            $consulta->bindValue(':order_code', $statistic->GetOrderCode(), PDO::PARAM_STR);
            $consulta->bindValue(':mesa_evaluation', $statistic->GetMesaEvaluation(), PDO::PARAM_INT);
            $consulta->bindValue(':mesa_code', $statistic->GetMesaCode(), PDO::PARAM_STR);
            $consulta->bindValue(':mozo_id', $statistic->GetMozoId(), PDO::PARAM_INT);
            $consulta->bindValue(':mozo_evaluation', $statistic->GetMozoEvaluation(), PDO::PARAM_INT);
            $consulta->bindValue(':restaurant_evaluation', $statistic->GetRestaurantEvaluation(), PDO::PARAM_INT);
            $consulta->bindValue(':comentario', $statistic->GetComentario(), PDO::PARAM_STR);
            $consulta->execute();

            foreach ($statistic->GetCocineros() as $cocinero) {
                $consulta = $objetoAccesoDato->RetornarConsulta('INSERT INTO cocinero_evaluacion
                    (order_code,evaluation,cocinero_id)
                    values(:order_code,:evaluation,:cocinero_id)');
                $consulta->bindValue(':order_code', $statistic->GetOrderCode(), PDO::PARAM_STR);
                $consulta->bindValue(':evaluation', $cocinero->evaluation, PDO::PARAM_INT);
                $consulta->bindValue(':cocinero_id', $cocinero->id, PDO::PARAM_INT);
                $consulta->execute();
            }

            $response = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, "Evaluacion entregada"); // $succesResponse);

        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $response;

    }

    /**
     * Solo para socios.
     */
    public static function GetOrderInfoToEvaluate(string $orderCode)
    {
        $statistics = [];
        try {

            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $mysqlQuery = 'SELECT orders.code as order_code,orders.id as order_id, mesas.code as mesa_code,
            users.name as mozo,users.id as mozo_id FROM orders
            INNER JOIN mesas ON orders.mesa_id = mesas.id
            INNER JOIN users ON orders.mozo_id = users.id
            WHERE orders.code = :code AND orders.status = :status ';

            $consulta = $objetoAccesoDato->RetornarConsulta($mysqlQuery);
            $consulta->bindValue(':code', $orderCode, PDO::PARAM_STR);
            $consulta->bindValue(':status', ORDER_STATUS::EATED, PDO::PARAM_INT);
            $consulta->execute();

            $row = $consulta->fetch();
            if ($row) {
                $statistic = new Statisctic();
                $statistic->SetMozo($row["mozo"]);
                $statistic->SetMozoId($row["mozo_id"]);
                $statistic->SetOrderCode($row["order_code"]);
                $statistic->SetMesaCode($row["mesa_code"]);
                $statistic->SetCocineros(Self::GetCocinerosFromOrderId($row["order_id"]));
                $return = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, $statistic->ToJson());
            } else {
                $return = new ApiResponse(REQUEST_ERROR_TYPE::GENERAL, "Sin ordenes coincientes  ");
            }

        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $return;
    }
    // "lo que más se vendió .
    // b- lo que menos se vendió .
    // c- los que no se entregaron en el tiempo estipulado.
    // d- los cancelados.
    // 9"
    public static function ResumenPedidos()
    {
        try {

            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $mysqlQuery = 'SELECT SUM(order_item.cant) total, order_item.item_id,items.name
            FROM order_item
            INNER JOIN items ON items.id = order_item.item_id
            GROUP BY order_item.item_id';

            $consulta = $objetoAccesoDato->RetornarConsulta($mysqlQuery);
            $consulta->execute();
            $max;
            $min;

            $flag = true;
            foreach ($consulta->fetchAll() as $row) {
                if ($flag) {
                    $flag = false;
                    $max["total"] = $row["total"];
                    $max["id"] = $row["item_id"];
                    $max["name"] = $row["name"];
                    $min["total"] = $row["total"];
                    $min["id"] = $row["item_id"];
                    $min["name"] = $row["name"];
                } else {
                    if ($max["total"] < $row["total"]) {
                        $max["total"] = $row["total"];
                        $max["id"] = $row["item_id"];
                        $max["name"] = $row["name"];
                    }
                    if ($min["total"] > $row["total"]) {
                        $min["total"] = $row["total"];
                        $min["id"] = $row["item_id"];
                        $min["name"] = $row["name"];
                    }
                }
            }

            $demoradosCancelados = Self::GetCanceladosDemorados();
            $respuesta["menosPedido"] = $min;
            $respuesta["masPedido"] = $max;
            $respuesta["demorados"] = $demoradosCancelados["demorados"];
            $respuesta["cancelados"] = $demoradosCancelados["cancelados"];
            $return = new ApiResponse(REQUEST_ERROR_TYPE::NOERROR, $respuesta);
        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $return;
    }

    private static function GetCanceladosDemorados()
    {
        $statistics = [];
        try {

            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $mysqlQuery = 'SELECT orders.id, orders.code FROM orders
            WHERE estimate_time < delivered_time
            AND status in (3,4)';
            $consulta = $objetoAccesoDato->RetornarConsulta($mysqlQuery);
            $consulta->execute();
            $demorados = [];
            foreach ($consulta->fetchAll() as $row) {
                $demorado["code"] = $row["code"];
                $demorado["id"] = $row["id"];
                array_push($demorados, $demorado);
            }

            $mysqlQuery = 'SELECT orders.id, orders.code FROM orders
            WHERE orders.status = 5';
            $consulta = $objetoAccesoDato->RetornarConsulta($mysqlQuery);

            $consulta->execute();
            $cancelados = [];
            foreach ($consulta->fetchAll() as $row) {
                $cancelado["code"] = $row["code"];
                $cancelado["id"] = $row["id"];
                array_push($cancelados, $cancelado);
            }

            $respuesta["demorados"] = $demorados;
            $respuesta["cancelados"] = $cancelados;
            return $respuesta;

        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $return;
    }

    public static function CheckEvaluation($orderCode)
    {
        $return = false;
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $mysqlQuery = 'SELECT id FROM order_evaluation

            WHERE order_evaluation.order_code = :code';
            $consulta = $objetoAccesoDato->RetornarConsulta($mysqlQuery);
            $consulta->bindValue(':code', $orderCode, PDO::PARAM_STR);
            $consulta->execute();
            $row = $consulta->fetchAll();
            if ($row) {
                $return = true;
            }

        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $return;
    }
    public static function ExistOrderCOde($orderCode)
    {
        $return = false;
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $mysqlQuery = 'SELECT id FROM orders
            WHERE orders.code = :code';
            $consulta = $objetoAccesoDato->RetornarConsulta($mysqlQuery);
            $consulta->bindValue(':code', $orderCode, PDO::PARAM_STR);
            $consulta->execute();
            $row = $consulta->fetchAll();
            if ($row) {
                $return = true;
            }

        } catch (PDOException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }

        return $return;
    }
}
