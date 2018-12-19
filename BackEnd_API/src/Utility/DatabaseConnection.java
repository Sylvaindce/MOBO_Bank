package Utility;

import java.sql.*;

/**
 * Created by Sylvain on 16/12/2016.
 */
public class DatabaseConnection {

    private Logs _logs = null;
    private Connection _connection = null;
    //private String _dbURI = "jdbc:oracle:thin:@//192.168.1.31:1521/PDBORCL";
    private String _dbURI = "jdbc:oracle:thin:@//192.168.43.188:1521/PDBORCL";
    //private String _user = "app_data";
    private String _user = "ibs_client";
    private String _password = "Tongji123";

    public DatabaseConnection(Logs logs) {

        _logs = logs;

        try {
            //Register Oracle Driver
            DriverManager.registerDriver(new oracle.jdbc.OracleDriver());

            //Start connection
            _connection = DriverManager.getConnection(_dbURI, _user, _password);

            //Check if connected
            if (_connection != null) {
                _logs.print_log("Client connected to OracleDB " + _dbURI);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public Connection getConnection() {
        return _connection;
    }
}
