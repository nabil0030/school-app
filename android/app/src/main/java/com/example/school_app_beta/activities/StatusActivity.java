package com.example.school_app_beta.activities;

import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Bundle;
import android.view.View;
import android.widget.*;
import androidx.appcompat.app.AppCompatActivity;

import com.example.school_app_beta.R;
import com.example.school_app_beta.network.ApiClient;

import org.json.JSONObject;

import java.io.InputStream;
import java.net.URL;
import java.io.IOException;

import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.Response;

public class StatusActivity extends AppCompatActivity {

    // ✅ Laravel server for Android Emulator
    private static final String BASE_URL = "http://10.0.2.2:8000/storage/";

    TextView tvStatus, tvCinMsg, tvBacMsg;
    ImageView imgCin, imgBac;
    Button btnGoProfile;

    String studentData;
    int studentId;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_status);

        // ===== BIND UI =====
        tvStatus = findViewById(R.id.tvStatus);
        tvCinMsg = findViewById(R.id.tvCinMsg);
        tvBacMsg = findViewById(R.id.tvBacMsg);
        imgCin = findViewById(R.id.imgCinStatus);
        imgBac = findViewById(R.id.imgBacStatus);
        btnGoProfile = findViewById(R.id.btnGoProfile);

        // ===== GET DATA FROM LOGIN =====
        studentData = getIntent().getStringExtra("student_data");

        if (studentData == null) {
            Toast.makeText(this, "Student data missing", Toast.LENGTH_LONG).show();
            finish();
            return;
        }

        try {
            JSONObject json = new JSONObject(studentData);
            JSONObject student = json.getJSONObject("student");

            studentId = student.getInt("id");

            String status = student.optString("status", "INCOMPLETE");
            tvStatus.setText("Status : " + status);

        } catch (Exception e) {
            Toast.makeText(this, "Invalid student data", Toast.LENGTH_LONG).show();
            finish();
            return;
        }

        // ===== BUTTON =====
        btnGoProfile.setOnClickListener(v -> {
            Intent i = new Intent(StatusActivity.this, ProfileActivity.class);
            i.putExtra("student_data", studentData);
            startActivity(i);
        });
    }

    // 🔁 AUTO REFRESH WHEN RETURNING FROM PROFILE
    @Override
    protected void onResume() {
        super.onResume();
        refreshStudentFromServer();
    }

    // 🔄 FETCH STUDENT AGAIN FROM LARAVEL
    private void refreshStudentFromServer() {

        ApiClient.get("/student/" + studentId, new Callback() {

            @Override
            public void onFailure(Call call, IOException e) {
                runOnUiThread(() ->
                        Toast.makeText(StatusActivity.this,
                                "Server error", Toast.LENGTH_SHORT).show()
                );
            }

            @Override
            public void onResponse(Call call, Response response) throws IOException {

                if (!response.isSuccessful()) return;

                String res = response.body() != null ? response.body().string() : "";

                try {
                    JSONObject student = new JSONObject(res);

                    runOnUiThread(() -> {

                        // ===== CIN =====
                        if (!student.isNull("cin_image")
                                && !student.optString("cin_image").isEmpty()) {

                            tvCinMsg.setVisibility(View.GONE);
                            imgCin.setVisibility(View.VISIBLE);
                            loadImage(BASE_URL + student.optString("cin_image"), imgCin);

                        } else {
                            imgCin.setVisibility(View.GONE);
                            tvCinMsg.setVisibility(View.VISIBLE);
                            tvCinMsg.setText("You didn't enter your CIN");
                        }

                        // ===== BAC =====
                        if (!student.isNull("bac_image")
                                && !student.optString("bac_image").isEmpty()) {

                            tvBacMsg.setVisibility(View.GONE);
                            imgBac.setVisibility(View.VISIBLE);
                            loadImage(BASE_URL + student.optString("bac_image"), imgBac);

                        } else {
                            imgBac.setVisibility(View.GONE);
                            tvBacMsg.setVisibility(View.VISIBLE);
                            tvBacMsg.setText("You didn't enter your BAC");
                        }
                    });

                } catch (Exception ignored) {}
            }
        });
    }

    // 🖼 LOAD IMAGE FROM URL
    private void loadImage(String url, ImageView img) {
        new Thread(() -> {
            try {
                InputStream in = new URL(url).openStream();
                Bitmap bmp = BitmapFactory.decodeStream(in);
                runOnUiThread(() -> img.setImageBitmap(bmp));
            } catch (Exception ignored) {}
        }).start();
    }
}
