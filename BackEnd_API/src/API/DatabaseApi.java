package API;

import java.net.InetSocketAddress;

import Rooting.*;
import Utility.Logs;
import Utility.Utilities;
import com.sun.net.httpserver.HttpServer;

public class DatabaseApi {

    private Integer _port = 1234;
    public Logs _logs = null;


    public DatabaseApi(Logs logs) throws Exception {

        _logs = logs;

        //CREATE UTILITY CLASS
        Utilities utilities = new Utilities(_logs);

        //SET SOCKET SERVER
        HttpServer server = HttpServer.create(new InetSocketAddress(_port), 0);

        //SET ROOTING
        server.createContext("/user", new UserHandler(utilities));
        server.createContext("/login", new LoginHandler(utilities));
        server.createContext("/signup", new CreateHandler(utilities));
        server.createContext("/balance", new BalanceHandler(utilities));
        server.createContext("/transfer", new TransferHandler(utilities));
        server.createContext("/transaction", new TransactionHandler(utilities));
        server.createContext("/customers", new CustomersAdminHandler(utilities));
        server.createContext("/logs", new LogsAdminHandler(utilities));
        server.createContext("/logbyid", new LogIdAdminHandler(utilities));


        //START SERVER
        server.setExecutor(null);
        server.start();

        _logs.print_log("Server start on port: " + String.valueOf(_port));
    }
}