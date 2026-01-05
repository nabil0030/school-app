package com.example.school_app_beta.activities;

import android.content.Intent;
import android.os.Bundle;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.example.school_app_beta.R;
import com.example.school_app_beta.network.ApiClient;

import java.io.IOException;

import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.FormBody;
import okhttp3.RequestBody;
import okhttp3.Response;

public class LoginActivity extends AppCompatActivity {

    EditText email, password;
    Button btnLogin;
    TextView goSignup;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        email = findViewById(R.id.email);
        password = findViewById(R.id.password);
        btnLogin = findViewById(R.id.btnLogin);
        goSignup = findViewById(R.id.goSignup);

        btnLogin.setOnClickListener(v -> login());

        goSignup.setOnClickListener(v -> {
            startActivity(new Intent(this, SignupActivity.class));
        });
    }

    private void login() {

        String sEmail = email.getText().toString().trim();
        String sPassword = password.getText().toString().trim();

        if (sEmail.isEmpty() || sPassword.isEmpty()) {
            Toast.makeText(this, "Veuillez remplir tous les champs", Toast.LENGTH_SHORT).show();
            return;
        }

        RequestBody body = new FormBody.Builder()
                .add("email", sEmail)
                .add("password", sPassword)
                .build();

        ApiClient.post("/login", body, new Callback() {

            @Override
            public void onFailure(Call call, IOException e) {
                runOnUiThread(() ->
                        Toast.makeText(
                                LoginActivity.this,
                                "Erreur de connexion au serveur ❌",
                                Toast.LENGTH_LONG
                        ).show()
                );
            }

            @Override
            public void onResponse(Call call, Response response) throws IOException {

                final String responseBody = response.body().string();

                runOnUiThread(() -> {

                    if (response.isSuccessful()) {

                        Toast.makeText(
                                LoginActivity.this,
                                "Connexion réussie ✅",
                                Toast.LENGTH_SHORT
                        ).show();

                        // Go to Profile
                        Intent intent = new Intent(LoginActivity.this, StatusActivity.class);
                        intent.putExtra("student_data", responseBody);
                        startActivity(intent);
                        finish();

                    } else {
                        Toast.makeText(
                                LoginActivity.this,
                                "Email ou mot de passe incorrect ❌",
                                Toast.LENGTH_LONG
                        ).show();
                    }
                });
            }
        });
    }
}
