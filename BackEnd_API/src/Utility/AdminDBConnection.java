package Utility;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

/**
 * Created by Sylvain on 12/01/2017.
 */
public class AdminDBConnection {

    private Logs _logs = null;
    private Connection _connection = null;
    //private String _dbURI = "jdbc:oracle:thin:@//192.168.1.31:1521/PDBORCL";
    private String _dbURI = "jdbc:oracle:thin:@//192.168.43.188:1521/PDBORCL";
    //private String _user = "app_data";
    private String _user = "ibs_admin";
    private String _password = "Tongji123";

    public AdminDBConnection(Logs logs) {

        _logs = logs;

        try {
            //Register Oracle Driver
            DriverManager.registerDriver(new oracle.jdbc.OracleDriver());

            //Start connection
            _connection = DriverManager.getConnection(_dbURI, _user, _password);

            //Check if connected
            if (_connection != null) {
                _logs.print_log("Admin connected to OracleDB " + _dbURI);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public Connection getConnection() {
        return _connection;
    }
}
