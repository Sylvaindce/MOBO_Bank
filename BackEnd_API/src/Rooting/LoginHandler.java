package Rooting;

import Utility.DatabaseConnection;
import Utility.Logs;
import Utility.Utilities;

import com.sun.net.httpserver.Headers;
import com.sun.net.httpserver.HttpExchange;
import com.sun.net.httpserver.HttpHandler;

import javax.json.Json;
import javax.json.JsonObject;
import java.io.IOException;
import java.io.OutputStream;
import java.sql.CallableStatement;
import java.sql.SQLException;
import java.sql.Types;
import java.util.Map;

/**
 * Created by Sylvain on 09/01/2017.
 */
public class LoginHandler implements HttpHandler {

    private Logs _logs = null;
    private DatabaseConnection _db = null;
    private Utilities _util = null;
    private int Param1 = -1;
    private int Param2 = -1;
    private int Role = 1;

    public LoginHandler(Utilities util) {
        _util = util;
        _db = _util.getDBC();
        _logs = _util.getLogs();
    }

    @Override
    public void handle(HttpExchange httpExchange) throws IOException {

        _logs.print_log("GET /login");

        //GET INCOME PARAMETERS
        Map<String, String> result = null;
        if (httpExchange.getRequestURI().getQuery() != null) {
            result = _util.getHTTPQuery().getQuery(httpExchange.getRequestURI().getQuery());
            _logs.print_log("FrontEnd -> . : "+result.toString());
        }

        //CHECK DATA AND CONNECT TO DB
        try {
            CallableStatement state = _db.getConnection().prepareCall("call client_pkg.is_login(?,?,?,?,?,?)");
            assert result != null;
            for (Map.Entry<String, String> entry : result.entrySet()) {
                switch (entry.getKey()) {
                    case "email":
                        state.setString(1, entry.getValue());
                        break;
                    case "password":
                        state.setString(2, entry.getValue());
                        break;
                }
            }
            state.registerOutParameter(3, Types.NUMERIC);
            state.registerOutParameter(4, Types.NUMERIC);
            state.registerOutParameter(5,Types.VARCHAR);
            state.registerOutParameter(6, Types.VARCHAR);
            state.execute();

            _logs.print_log("DB -> . : "+state.getInt(3)+" - "+state.getString(4)+" - "+state.getString(5)+" - "+state.getString(6));

            //MANAGE ANSWER
            Param1 = state.getInt(3);
            if (state.getString(6).equals("true"))
                Param2 = 0;
            Role = state.getInt(4);
        } catch (SQLException e) {
            e.printStackTrace();
        }

        //Create JSON Object
        JsonObject json = Json.createObjectBuilder()
                .add("state", Param2)
                .add("userid", Param1)
                .add("role", Role)
                .build();

        //PREPARE ANSWER HEADER
        Headers h = httpExchange.getResponseHeaders();
        h.add("Content-Type", "application/json");
        h.add("Accept-Encoding", "UTF-8");
        h.add("Access-Control-Allow-Origin", "*");

        String response = String.valueOf(json);

        _logs.print_log("FrontEnd <- . : "+ response);

        httpExchange.sendResponseHeaders(200, response.length());
        OutputStream os = httpExchange.getResponseBody();
        os.write(response.getBytes(), 0, response.length());
        os.close();
    }
}
