module com.example.demo1_coba {
    requires javafx.controls;
    requires javafx.fxml;

    requires org.kordamp.bootstrapfx.core;

    opens com.example.demo1_coba to javafx.fxml;
    exports com.example.demo1_coba;
}