package com.example.school_app_beta.activities;

import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.widget.*;
import androidx.appcompat.app.AppCompatActivity;

import com.example.school_app_beta.R;
import com.example.school_app_beta.network.ApiClient;

import org.json.JSONObject;

import java.io.*;

import okhttp3.*;

public class BacActivity extends AppCompatActivity {

    EditText tBacNumber, tBacYear, tBacMention;
    Button btnUploadBac, btnConfirmBac;
    ImageView imgBac;
    String bacImagePath = "";

    Uri bacUri;
    int studentId;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_bac);

        studentId = getIntent().getIntExtra("student_id", -1);

        tBacNumber = findViewById(R.id.tBacNumber);
        tBacYear = findViewById(R.id.tBacYear);
        tBacMention = findViewById(R.id.tBacMention);

        btnUploadBac = findViewById(R.id.btnUploadBac);
        btnConfirmBac = findViewById(R.id.btnConfirmBac);
        imgBac = findViewById(R.id.imgBac);

        btnUploadBac.setOnClickListener(v -> openGallery());
        btnConfirmBac.setOnClickListener(v -> saveBac());
    }

    private void openGallery() {
        Intent i = new Intent(Intent.ACTION_PICK);
        i.setType("image/*");
        startActivityForResult(i, 200);
    }

    @Override
    protected void onActivityResult(int req, int res, Intent data) {
        super.onActivityResult(req, res, data);

        if (req == 200 && res == RESULT_OK && data != null) {
            bacUri = data.getData();
            imgBac.setImageURI(bacUri);
            uploadBac();
        }
    }

    // ================= UPLOAD + OCR =================
    private void uploadBac() {
        try {
            InputStream in = getContentResolver().openInputStream(bacUri);
            ByteArrayOutputStream buffer = new ByteArrayOutputStream();

            byte[] bytes = new byte[1024];
            int n;
            while ((n = in.read(bytes)) != -1) buffer.write(bytes, 0, n);

            RequestBody file = RequestBody.create(
                    MediaType.parse("image/*"),
                    buffer.toByteArray()
            );

            MultipartBody body = new MultipartBody.Builder()
                    .setType(MultipartBody.FORM)
                    .addFormDataPart("student_id", String.valueOf(studentId))
                    .addFormDataPart("bac_image", "bac.jpg", file)
                    .build();

            ApiClient.post("/bac/upload", body, new Callback() {

                @Override
                public void onFailure(Call call, IOException e) {}

                @Override
                public void onResponse(Call call, Response response) throws IOException {
                    if (!response.isSuccessful()) return;

                    String res = response.body() != null ? response.body().string() : "";

                    runOnUiThread(() -> {
                        try {
                            JSONObject json = new JSONObject(res);
                            JSONObject ocr = json.getJSONObject("ocr");

                            tBacNumber.setText(ocr.optString("bac_number"));
                            tBacYear.setText(ocr.optString("bac_year"));
                            tBacMention.setText(ocr.optString("bac_mention"));

                        } catch (Exception ignored) {}
                    });
                }
            });

        } catch (Exception ignored) {}
    }

    // ================= SAVE BAC =================
    private void saveBac() {

        if (bacUri == null) return;

        try {
            InputStream in = getContentResolver().openInputStream(bacUri);
            ByteArrayOutputStream buffer = new ByteArrayOutputStream();

            byte[] bytes = new byte[1024];
            int n;
            while ((n = in.read(bytes)) != -1) buffer.write(bytes, 0, n);

            RequestBody file = RequestBody.create(
                    MediaType.parse("image/*"),
                    buffer.toByteArray()
            );

            MultipartBody body = new MultipartBody.Builder()
                    .setType(MultipartBody.FORM)
                    .addFormDataPart("student_id", String.valueOf(studentId))
                    .addFormDataPart("bac_number", tBacNumber.getText().toString())
                    .addFormDataPart("bac_year", tBacYear.getText().toString())
                    .addFormDataPart("bac_mention", tBacMention.getText().toString())
                    .addFormDataPart("bac_image", "bac.jpg", file)
                    .build();

            ApiClient.post("/bac/save", body, new Callback() {

                @Override
                public void onFailure(Call call, IOException e) {}

                @Override
                public void onResponse(Call call, Response response) {
                    runOnUiThread(() ->
                            Toast.makeText(BacActivity.this,
                                    response.isSuccessful()
                                            ? "BAC saved ✅"
                                            : "Save error ❌",
                                    Toast.LENGTH_LONG).show()
                    );
                }
            });

        } catch (Exception ignored) {}
    }
}
