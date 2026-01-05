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

public class ProfileActivity extends AppCompatActivity {


    TextView tvNom, tvPrenom, tvEmail, tvCin;


    EditText tNom, tPrenomTable, tCinOcr;
    TextView tvOcrRaw;

    Button btnUploadCin, btnConfirm;
    ImageView imgCin;

    Uri cinUri;
    int studentId;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_profile);

        // DB
        tvNom = findViewById(R.id.tvNom);
        tvPrenom = findViewById(R.id.tvPrenom);
        tvEmail = findViewById(R.id.tvEmail);
        tvCin = findViewById(R.id.tvCin);

        // OCR
        tNom = findViewById(R.id.tNom);
        tPrenomTable = findViewById(R.id.tPrenomTable);
        tCinOcr = findViewById(R.id.tCinOcr);
        tvOcrRaw = findViewById(R.id.tvOcrRaw);

        btnUploadCin = findViewById(R.id.btnUploadCin);
        btnConfirm = findViewById(R.id.btnConfirm);
        imgCin = findViewById(R.id.imgCin);

        // ================= STUDENT DATA =================
        try {
            JSONObject student = new JSONObject(
                    getIntent().getStringExtra("student_data")
            ).getJSONObject("student");

            studentId = student.getInt("id");

            tvNom.setText("Nom : " + student.getString("nom"));
            tvPrenom.setText("Prénom : " + student.getString("prenom"));
            tvEmail.setText("Email : " + student.getString("email"));
            tvCin.setText("CIN : " + student.getString("cin"));

        } catch (Exception e) {
            Toast.makeText(this, "Erreur de chargement du profil", Toast.LENGTH_LONG).show();
        }

        btnUploadCin.setOnClickListener(v -> openGallery());
        Button btnGoToBac = findViewById(R.id.btnGoToBac);

        btnGoToBac.setOnClickListener(v -> {
            Intent intent = new Intent(ProfileActivity.this, BacActivity.class);
            intent.putExtra("student_id", studentId);
            startActivity(intent);
        });

        // ================= CONFIRM UPDATE =================
        btnConfirm.setOnClickListener(v -> {

            if (cinUri == null) {
                Toast.makeText(this,
                        "Veuillez sélectionner une image CIN",
                        Toast.LENGTH_LONG).show();
                return;
            }

            try {
                InputStream in = getContentResolver().openInputStream(cinUri);
                ByteArrayOutputStream buffer = new ByteArrayOutputStream();

                byte[] bytes = new byte[1024];
                int n;
                while ((n = in.read(bytes)) != -1) {
                    buffer.write(bytes, 0, n);
                }

                RequestBody file = RequestBody.create(
                        buffer.toByteArray(),
                        MediaType.parse("image/*")
                );

                MultipartBody body = new MultipartBody.Builder()
                        .setType(MultipartBody.FORM)
                        .addFormDataPart("student_id", String.valueOf(studentId))
                        .addFormDataPart("nom", tNom.getText().toString())
                        .addFormDataPart("prenom", tPrenomTable.getText().toString())
                        .addFormDataPart("cin", tCinOcr.getText().toString())
                        .addFormDataPart("cin_image", "cin.jpg", file)
                        .build();

                ApiClient.post("/update-profile", body, new Callback() {

                    @Override
                    public void onFailure(Call call, IOException e) {
                        runOnUiThread(() ->
                                Toast.makeText(ProfileActivity.this,
                                        "Erreur de connexion au serveur",
                                        Toast.LENGTH_LONG).show()
                        );
                    }

                    @Override
                    public void onResponse(Call call, Response response) {
                        runOnUiThread(() ->
                                Toast.makeText(ProfileActivity.this,
                                        "Modifications enregistrées avec succès",
                                        Toast.LENGTH_LONG).show()
                        );
                    }
                });

            } catch (Exception e) {
                Toast.makeText(this,
                        "Erreur lors de l'envoi des données",
                        Toast.LENGTH_LONG).show();
            }
        });
    }

    // ================= OPEN GALLERY =================
    private void openGallery() {
        Intent i = new Intent(Intent.ACTION_PICK);
        i.setType("image/*");
        startActivityForResult(i, 100);
    }

    @Override
    protected void onActivityResult(int req, int res, Intent data) {
        super.onActivityResult(req, res, data);

        if (req == 100 && res == RESULT_OK && data != null) {
            cinUri = data.getData();
            imgCin.setImageURI(cinUri);
            imgCin.setVisibility(ImageView.VISIBLE);
            uploadCin();
        }
    }

    // ================= UPLOAD CIN + OCR =================
    private void uploadCin() {
        try {
            InputStream in = getContentResolver().openInputStream(cinUri);
            ByteArrayOutputStream buffer = new ByteArrayOutputStream();

            byte[] bytes = new byte[1024];
            int n;
            while ((n = in.read(bytes)) != -1) {
                buffer.write(bytes, 0, n);
            }

            RequestBody file = RequestBody.create(
                    buffer.toByteArray(),
                    MediaType.parse("image/*")
            );

            MultipartBody body = new MultipartBody.Builder()
                    .setType(MultipartBody.FORM)
                    .addFormDataPart("student_id", String.valueOf(studentId))
                    .addFormDataPart("cin_image", "cin.jpg", file)
                    .build();

            ApiClient.post("/upload-cin", body, new Callback() {

                @Override
                public void onFailure(Call call, IOException e) {
                    runOnUiThread(() ->
                            Toast.makeText(ProfileActivity.this,
                                    "Erreur de connexion au serveur",
                                    Toast.LENGTH_LONG).show()
                    );
                }

                @Override
                public void onResponse(Call call, Response response) throws IOException {

                    if (!response.isSuccessful()) return;

                    String res = response.body() != null
                            ? response.body().string()
                            : "";

                    runOnUiThread(() -> {
                        try {
                            JSONObject json = new JSONObject(res);

                            JSONObject ocr = json.getJSONObject("ocr");
                            JSONObject messages = json.getJSONObject("messages");

                            tNom.setText(ocr.optString("nom", ""));
                            tPrenomTable.setText(ocr.optString("prenom", ""));
                            tCinOcr.setText(ocr.optString("cin", ""));

                            if (!messages.isNull("nom"))
                                tNom.setHint(messages.getString("nom"));

                            if (!messages.isNull("prenom"))
                                tPrenomTable.setHint(messages.getString("prenom"));

                            tvOcrRaw.setText(json.optString("raw_text", ""));

                        } catch (Exception e) {
                            Toast.makeText(ProfileActivity.this,
                                    "Erreur d'analyse des données",
                                    Toast.LENGTH_LONG).show();
                        }
                    });
                }
            });

        } catch (Exception e) {
            Toast.makeText(this, "Erreur de traitement de l'image", Toast.LENGTH_LONG).show();
        }
    }
}