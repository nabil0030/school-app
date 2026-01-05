package com.example.school_app_beta.activities;

import static androidx.core.content.ContextCompat.startActivity;

import android.content.Intent;
import android.os.Bundle;
import android.widget.Button;

import androidx.appcompat.app.AppCompatActivity;

import com.example.school_app_beta.R;

public class HomeActivity extends AppCompatActivity {

    Button btnLogin, btnSignup;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_home);

        btnLogin = findViewById(R.id.btnLogin);
        btnSignup = findViewById(R.id.btnSignup);

        // LOGIN button - goes to LoginActivity
        btnLogin.setOnClickListener(v -> {
            Intent intent = new Intent(HomeActivity.this, LoginActivity.class);
            startActivity(intent);
        });

        // SIGNUP button - goes to SignupActivity
        btnSignup.setOnClickListener(v -> {
            Intent intent = new Intent(HomeActivity.this, SignupActivity.class);
            startActivity(intent);
        });
    }
}