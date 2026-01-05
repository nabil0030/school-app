package com.example.school_app_beta.network;

import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.RequestBody;
import okhttp3.Callback;

public class ApiClient {

    public static final String BASE_URL = "http://10.0.2.2:8000/api";

    private static final OkHttpClient client = new OkHttpClient();

    public static void post(String endpoint, RequestBody body, Callback callback) {
        Request request = new Request.Builder()
                .url(BASE_URL + endpoint)
                .post(body)
                .build();

        client.newCall(request).enqueue(callback);
    }
    public static void get(String endpoint, Callback callback) {

        Request request = new Request.Builder()
                .url(BASE_URL + endpoint)
                .get()
                .build();

        client.newCall(request).enqueue(callback);
    }
}
