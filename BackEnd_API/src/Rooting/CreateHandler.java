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
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.sql.Types;
import java.util.Map;

/**
 * Created by Sylvain on 30/12/2016.
 */
public class CreateHandler implements HttpHandler {

    private Logs _logs = null;
    private DatabaseConnection _db = null;
    private Utilities _util = null;
    private String AccountNumber = "";
    private Integer AccountState = -1;
    private Integer UserId = -1;

    public CreateHandler(Utilities util) {
        _util = util;
        _db = _util.getDBC();
        _logs = _util.getLogs();
    }

    @Override
    public void handle(HttpExchange httpExchange) throws IOException {

        _logs.print_log("GET /signup");

        Map<String, String> result = null;
        if (httpExchange.getRequestURI().getQuery() != null) {
            result = _util.getHTTPQuery().getQuery(httpExchange.getRequestURI().getQuery());
            _logs.print_log("FrontEnd -> . : "+result.toString());
        }

        String jobquery = "call client_pkg.create_account(?,?,?,?,?,?,?,?,?)";
        try {
            CallableStatement state = _db.getConnection().prepareCall(jobquery);
            for (Map.Entry<String, String> entry : result.entrySet()) {
                switch (entry.getKey()) {
                    case "firstname":
                        state.setString(1, entry.getValue());
                        break;
                    case "lastname":
                        state.setString(2, entry.getValue());
                        break;
                    case "password":
                        state.setString(3, entry.getValue());
                        break;
                    case "email":
                        state.setString(4, entry.getValue());
                        break;
                    case "phone":
                        state.setString(5, entry.getValue());
                        break;
                    case "address":
                        state.setString(6, entry.getValue());
                        break;
                }
            }
            state.registerOutParameter(7, Types.VARCHAR);
            state.registerOutParameter(8, Types.VARCHAR);
            state.registerOutParameter(9, Types.INTEGER);
            state.execute();
            UserId = state.getInt(9);
            if (state.getString(8).equals("true")) {
                AccountState = 0;
            }
            AccountNumber = state.getString(7);
            _logs.print_log("DB -> . : "+state.getString(7)+" - "+state.getString(8)+" - "+String.valueOf(state.getInt(9)));
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
                .add("state", AccountState)
                .add("userid", UserId)
                .add("accountnb", AccountNumber)
                .build();

        String response = json.toString();

        _logs.print_log("FrontEnd <- . : "+response);

        httpExchange.sendResponseHeaders(200, response.length());
        OutputStream os = httpExchange.getResponseBody();
        os.write(response.getBytes());
        os.close();
    }
}
