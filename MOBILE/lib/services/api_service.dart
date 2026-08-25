import 'dart:convert';
import 'package:http/http.dart' as http;

class ApiService {
  // GANTI SESUAI IP LARAVEL
  static const String baseUrl = "https://sispussman3padang.my.id/api";

  // REGISTER
  static Future<Map<String, dynamic>> register({
    required String name,
    required String email,
    required String password,
    required String role,
    required String nisNip,
    required String phone,
    required String address,
  }) async {
    final response = await http.post(
      Uri.parse("$baseUrl/register"),

      headers: {"Accept": "application/json"},

      body: {
        "name": name,
        "email": email,
        "password": password,
        "role": role,
        "nis_nip": nisNip,
        "phone": phone,
        "address": address,
      },
    );

    return jsonDecode(response.body);
  }

  // LOGIN
  static Future<Map<String, dynamic>> login({
    required String nisNip,
    required String password,
  }) async {
    final response = await http.post(
      Uri.parse("$baseUrl/login"),
      headers: {"Accept": "application/json"},
      body: {"nis_nip": nisNip, "password": password},
    );

    print("STATUS LOGIN = ${response.statusCode}");
    print("BODY LOGIN = ${response.body}");

    return jsonDecode(response.body);
  }

  //buku
  // GET BOOKS
  static Future<List<dynamic>> getBooks() async {
    final response = await http.get(
      Uri.parse("$baseUrl/books"),
      headers: {"Accept": "application/json"},
    );

    print("STATUS BOOKS = ${response.statusCode}");
    print("BODY BOOKS = ${response.body}");

    final data = jsonDecode(response.body);

    return data['data'] ?? [];
  }

  // GET DASHBOARD
  static Future<Map<String, dynamic>> getDashboard(int userId) async {
    final response = await http.get(
      Uri.parse("$baseUrl/dashboard/$userId"),
      headers: {"Accept": "application/json"},
    );

    print("STATUS DASHBOARD = ${response.statusCode}");

    print("BODY DASHBOARD = ${response.body}");

    return jsonDecode(response.body);
  }

  static Future<List<dynamic>> getMyBorrowings(int userId) async {
    final response = await http.get(
      Uri.parse("$baseUrl/my-borrowings/$userId"),

      headers: {"Accept": "application/json"},
    );

    final data = jsonDecode(response.body);

    return data['data'] ?? [];
  }

  static Future<Map<String, dynamic>> requestReturn(int borrowingId) async {
    final response = await http.post(
      Uri.parse("$baseUrl/request-return"),

      headers: {"Accept": "application/json"},

      body: {"borrowing_id": borrowingId.toString()},
    );

    return jsonDecode(response.body);
  }

  static Future<Map<String, dynamic>> borrowBook({
    required int userId,

    required int bookId,

    required String loanType,

    bool isCollective = false,

    String className = "",

    int quantity = 1,
  }) async {
    final response = await http.post(
      Uri.parse("$baseUrl/borrowings"),

      headers: {"Accept": "application/json"},

      body: {
        "user_id": userId.toString(),

        "book_id[0]": bookId.toString(),

        "loan_type": loanType,

        "is_collective": isCollective ? "1" : "0",

        "class_name": className,

        "quantity": quantity.toString(),
      },
    );

    return jsonDecode(response.body);
  }

  static Future<Map<String, dynamic>> borrowBooks({
    required int userId,
    required List<int> bookIds,
    required String loanType,
  }) async {
    Map<String, String> body = {
      "user_id": userId.toString(),

      "loan_type": loanType,
    };

    for (int i = 0; i < bookIds.length; i++) {
      body["book_id[$i]"] = bookIds[i].toString();
    }

    final response = await http.post(
      Uri.parse("$baseUrl/borrowings"),

      headers: {"Accept": "application/json"},

      body: body,
    );

    return jsonDecode(response.body);
  }

  static Future<Map<String, dynamic>> getProfile(int userId) async {
    final response = await http.get(
      Uri.parse("$baseUrl/profile/$userId"),

      headers: {"Accept": "application/json"},
    );

    return jsonDecode(response.body);
  }

  static Future<Map<String, dynamic>> getRanking(int userId) async {
    final response = await http.get(
      Uri.parse("$baseUrl/ranking/$userId"),

      headers: {"Accept": "application/json"},
    );

    return jsonDecode(response.body);
  }

  static Future<List<dynamic>> getLeaderboard(String role) async {
    final response = await http.get(
      Uri.parse("$baseUrl/leaderboard/$role"),

      headers: {"Accept": "application/json"},
    );

    final data = jsonDecode(response.body);

    return data['data'] ?? [];
  }

  static Future<Map<String, dynamic>> changePassword({
    required int userId,

    required String oldPassword,

    required String newPassword,
  }) async {
    final response = await http.post(
      Uri.parse("$baseUrl/change-password"),

      headers: {"Accept": "application/json"},

      body: {
        "user_id": userId.toString(),

        "old_password": oldPassword,

        "new_password": newPassword,
      },
    );

    return jsonDecode(response.body);
  }

  static Future<Map<String, dynamic>> reportLostBook(int borrowingId) async {
    final response = await http.post(
      Uri.parse("$baseUrl/report-lost-book"),

      headers: {"Accept": "application/json"},

      body: {"borrowing_id": borrowingId.toString()},
    );

    return jsonDecode(response.body);
  }

  static Future<Map<String, dynamic>> updateFcmToken({
    required int userId,
    required String token,
  }) async {
    final response = await http.post(
      Uri.parse("$baseUrl/update-fcm-token"),
      headers: {"Accept": "application/json"},
      body: {"user_id": userId.toString(), "fcm_token": token},
    );

    return jsonDecode(response.body);
  }

  static Future<Map<String, dynamic>> logout() async {
    final response = await http.post(
      Uri.parse("$baseUrl/logout"),

      headers: {"Accept": "application/json"},
    );

    return jsonDecode(response.body);
  }
  static Future<Map<String, dynamic>> updateProfile({
  required int userId,
  required String name,
  required String email,
  required String phone,
  required String address,
}) async {

  final response = await http.post(
    Uri.parse("$baseUrl/update-profile"),

    headers: {
      "Accept": "application/json",
    },

    body: {
      "user_id": userId.toString(),
      "name": name,
      "email": email,
      "phone": phone,
      "address": address,
    },
  );

  return jsonDecode(response.body);
}
}
