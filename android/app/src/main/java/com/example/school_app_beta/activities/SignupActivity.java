package com.example.school_app_beta.activities;

import android.os.Bundle;
import android.widget.Button;
import android.widget.EditText;
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

public class SignupActivity extends AppCompatActivity {

    EditText nom, prenom, email, cin, password;
    Button btnSignup;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_signup);

        nom = findViewById(R.id.nom);
        prenom = findViewById(R.id.prenom);
        email = findViewById(R.id.email);
        cin = findViewById(R.id.cin);
        password = findViewById(R.id.password);
        btnSignup = findViewById(R.id.btnSignup);

        btnSignup.setOnClickListener(v -> signup());
    }

    private void signup() {

        RequestBody body = new FormBody.Builder()
                .add("nom", nom.getText().toString())
                .add("prenom", prenom.getText().toString())
                .add("email", email.getText().toString())
                .add("cin", cin.getText().toString())
                .add("password", password.getText().toString())
                .build();

        ApiClient.post("/signup", body, new Callback() {

            @Override
            public void onFailure(Call call, IOException e) {
                runOnUiThread(() ->
                        Toast.makeText(
                                SignupActivity.this,
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
                                SignupActivity.this,
                                "Inscription réussie ✅",
                                Toast.LENGTH_LONG
                        ).show();
                        finish(); // retour vers Login
                    } else {
                        Toast.makeText(
                                SignupActivity.this,
                                "Erreur signup ❌ : " + responseBody,
                                Toast.LENGTH_LONG
                        ).show();
                    }
                });
            }
        });
    }
}
