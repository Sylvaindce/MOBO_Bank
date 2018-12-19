package Rooting;

import Utility.DatabaseConnection;
import Utility.Logs;
import Utility.Utilities;
import com.sun.net.httpserver.Headers;
import com.sun.net.httpserver.HttpExchange;
import com.sun.net.httpserver.HttpHandler;
import oracle.jdbc.OracleTypes;

import javax.json.Json;
import javax.json.JsonObject;
import java.io.IOException;
import java.io.OutputStream;
import java.sql.CallableStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Types;
import java.util.Map;

/**
 * Created by Sylvain on 11/01/2017.
 */
public class BalanceHandler implements HttpHandler {

    private Logs _logs = null;
    private DatabaseConnection _db = null;
    private Utilities _util = null;

    private Integer userid = -1;
    private Double Balance = -1.0;

    public BalanceHandler(Utilities util) {
        _util = util;
        _db = _util.getDBC();
        _logs = _util.getLogs();
    }

    @Override
    public void handle(HttpExchange httpExchange) throws IOException {
        _logs.print_log("GET /balance");

        //GET INCOME PARAMETERS
        if (httpExchange.getRequestURI().getQuery() != null) {
            Map<String, String> result = _util.getHTTPQuery().getQuery(httpExchange.getRequestURI().getQuery());
            _logs.print_log("FrontEnd -> . : "+result.toString());
            userid = Integer.parseInt(result.entrySet().iterator().next().getValue());
        }

        try {
            CallableStatement state = _db.getConnection().prepareCall("call client_pkg.get_balance(?,?)");
            state.setInt(1, userid);
            state.registerOutParameter(2, Types.NUMERIC);
            state.execute();

            //MANAGE ANSWER
            Balance = state.getDouble(2);

            _logs.print_log("DB -> . : "+String.valueOf(Balance));
        } catch (SQLException e) {
            e.printStackTrace();
        }

        //PREPARE ANSWER HEADER
        Headers h = httpExchange.getResponseHeaders();
        h.add("Content-Type", "application/json");
        h.add("Accept-Encoding", "UTF-8");
        h.add("Access-Control-Allow-Origin", "*");

        //Create JSON Object
        JsonObject json = Json.createObjectBuilder()
                .add("balance", Balance)
                .add("userid", userid)
                .build();

        String response = json.toString();

        _logs.print_log("FrontEnd <- . : "+ response);

        httpExchange.sendResponseHeaders(200, response.length());
        OutputStream os = httpExchange.getResponseBody();
        os.write(response.getBytes());
        os.close();
    }
}
