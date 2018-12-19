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
import javax.json.stream.JsonParser;
import java.io.IOException;
import java.io.OutputStream;
import java.sql.Array;
import java.sql.CallableStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.Map;

import org.json.simple.JSONArray;
import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;

/**
 * Created by Sylvain on 11/01/2017.
 */
public class TransactionHandler implements HttpHandler {

    private Logs _logs = null;
    private DatabaseConnection _db = null;
    private Utilities _util = null;

    private Integer userid = -1;

    private JSONArray amountList = null;
    private JSONArray dateList = null;
    private JSONArray accountList = null;

    public TransactionHandler(Utilities util) {
        _util = util;
        _db = _util.getDBC();
        _logs = _util.getLogs();

        amountList = new JSONArray();
        dateList = new JSONArray();
        accountList = new JSONArray();
    }

    @Override
    public void handle(HttpExchange httpExchange) throws IOException {

        _logs.print_log("GET /transaction");

        //GET INCOME PARAMETERS
        if (httpExchange.getRequestURI().getQuery() != null) {
            Map<String, String> result = _util.getHTTPQuery().getQuery(httpExchange.getRequestURI().getQuery());
            _logs.print_log("FrontEnd -> . : "+result.toString());
            userid = Integer.parseInt(result.entrySet().iterator().next().getValue());
        }

        //CHECK DATA AND CONNECT TO DB

        try {
            CallableStatement state = _db.getConnection().prepareCall("call client_pkg.get_my_transaction(?,?)");
            state.setInt(1, userid);
            state.registerOutParameter(2, OracleTypes.CURSOR);
            state.execute();

            //MANAGE ANSWER
            ResultSet cursor = (ResultSet) state.getObject(2);

            amountList.clear();
            dateList.clear();
            accountList.clear();

            while(cursor.next()) {
                amountList.add(cursor.getString("AMOUNT"));
                dateList.add(cursor.getString("TXNDATE"));
                accountList.add(cursor.getString("ACCOUNTNUMBER"));
                _logs.print_log("DB -> . : "+cursor.getString("AMOUNT")+" "+cursor.getString("TXNDATE")+" "+cursor.getString("ACCOUNTNUMBER"));
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }

        //Create JSON Object
        JsonObject json = Json.createObjectBuilder()
                .add("userid", userid)
                .add("amount", amountList.toJSONString())
                .add("date", dateList.toJSONString())
                .add("account", accountList.toJSONString())
                .add("size", amountList.size())
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
