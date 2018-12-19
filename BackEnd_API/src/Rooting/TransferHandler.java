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
public class TransferHandler implements HttpHandler {

    private Logs _logs = null;
    private DatabaseConnection _db = null;
    private Utilities _util = null;

    private Integer userid = -1;
    private Integer AccountState = -1;

    public TransferHandler(Utilities util) {
        _util = util;
        _db = _util.getDBC();
        _logs = _util.getLogs();
    }

    @Override
    public void handle(HttpExchange httpExchange) throws IOException {
        _logs.print_log("GET /transfer");

        //GET INCOME PARAMETERS
        Map<String, String> result = null;
        if (httpExchange.getRequestURI().getQuery() != null) {
            result = _util.getHTTPQuery().getQuery(httpExchange.getRequestURI().getQuery());
            _logs.print_log("FrontEnd -> . : "+result.toString());
        }

        //CHECK DATA AND CONNECT TO DB

        try {
            CallableStatement state = _db.getConnection().prepareCall("call client_pkg.transfer_money(?,?,?,?)");

            for (Map.Entry<String, String> entry : result.entrySet()) {
                switch (entry.getKey()) {
                    case "balance":
                        state.setString(3, entry.getValue());
                        break;
                    case "userid":
                        userid = Integer.parseInt(entry.getValue());
                        state.setString(1, entry.getValue());
                        break;
                    case "account":
                        state.setString(2, entry.getValue());
                        break;
                }
            }
            state.registerOutParameter(4, Types.VARCHAR);
            state.execute();

            //MANAGE ANSWER
            if (state.getString(4).equals("TRUE")) {
                AccountState = 0;
            }

            _logs.print_log("DB -> . : "+state.getString(4));
        } catch (SQLException e) {
            e.printStackTrace();
        }

        //Create JSON Object
        JsonObject json = Json.createObjectBuilder()
                .add("state", AccountState)
                .add("userid", userid)
                .build();

        //PREPARE ANSWER HEADER
        Headers h = httpExchange.getResponseHeaders();
        h.add("Content-Type", "application/json");
        h.add("Accept-Encoding", "UTF-8");
        h.add("Access-Control-Allow-Origin", "*");

        String response = json.toString();

        _logs.print_log("FrontEnd <- . : "+ response);

        httpExchange.sendResponseHeaders(200, response.length());
        OutputStream os = httpExchange.getResponseBody();
        os.write(response.getBytes());
        os.close();
    }
}
