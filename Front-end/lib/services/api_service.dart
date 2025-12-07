import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../utils/constants.dart';

class ApiService {
  Future<Map<String, String>> _getHeaders() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');
    return {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      if (token != null) 'Authorization': 'Bearer $token',
    };
  }

  Future<http.Response> register(
      String name, String email, String password) async {
    final url = Uri.parse('${Constants.baseUrl}/auth/register');
    return await http.post(
      url,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: jsonEncode({
        'name': name,
        'email': email,
        'password': password,
        'password_confirmation': password,
      }),
    );
  }

  Future<http.Response> login(String email, String password) async {
    final url = Uri.parse('${Constants.baseUrl}/auth/login');
    return await http.post(
      url,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: jsonEncode({'email': email, 'password': password}),
    );
  }

  Future<http.Response> logout() async {
    final url = Uri.parse('${Constants.baseUrl}/auth/logout');
    final headers = await _getHeaders();
    return await http.post(url, headers: headers);
  }

  Future<http.Response> getUser() async {
    final url = Uri.parse('${Constants.baseUrl}/auth/me');
    final headers = await _getHeaders();
    return await http.get(url, headers: headers);
  }

  Future<http.Response> getBalance() async {
    final url = Uri.parse('${Constants.baseUrl}/reports/monthly-summary');
    final headers = await _getHeaders();
    return await http.get(url, headers: headers);
  }

  Future<http.Response> getTransactions() async {
    final url = Uri.parse('${Constants.baseUrl}/transactions');
    final headers = await _getHeaders();
    return await http.get(url, headers: headers);
  }

  Future<http.Response> addTransaction(Map<String, dynamic> data) async {
    final url = Uri.parse('${Constants.baseUrl}/transactions');
    final headers = await _getHeaders();
    return await http.post(url, headers: headers, body: jsonEncode(data));
  }

  Future<http.Response> getCategories() async {
    final url = Uri.parse('${Constants.baseUrl}/categories');
    final headers = await _getHeaders();
    return await http.get(url, headers: headers);
  }

  // Admin endpoints
  Future<http.Response> getAllUsers() async {
    final url = Uri.parse('${Constants.baseUrl}/users');
    final headers = await _getHeaders();
    return await http.get(url, headers: headers);
  }

  Future<http.Response> muteUser(int userId) async {
    final url = Uri.parse('${Constants.baseUrl}/users/$userId/mute');
    final headers = await _getHeaders();
    return await http.post(url, headers: headers);
  }

  Future<http.Response> banUser(int userId) async {
    final url = Uri.parse('${Constants.baseUrl}/users/$userId/ban');
    final headers = await _getHeaders();
    return await http.post(url, headers: headers);
  }

  Future<http.Response> activateUser(int userId) async {
    final url = Uri.parse('${Constants.baseUrl}/users/$userId/activate');
    final headers = await _getHeaders();
    return await http.post(url, headers: headers);
  }

  Future<http.Response> deleteUser(int userId) async {
    final url = Uri.parse('${Constants.baseUrl}/users/delete/$userId');
    final headers = await _getHeaders();
    return await http.delete(url, headers: headers);
  }

  // Currency endpoints
  Future<http.Response> getCurrencies() async {
    final url = Uri.parse('${Constants.baseUrl}/currency/list');
    final headers = await _getHeaders();
    return await http.get(url, headers: headers);
  }

  Future<http.Response> convertCurrency(
      double amount, String from, String to) async {
    final url = Uri.parse('${Constants.baseUrl}/currency/convert');
    final headers = await _getHeaders();
    return await http.post(
      url,
      headers: headers,
      body: jsonEncode({
        'amount': amount,
        'from': from,
        'to': to,
      }),
    );
  }
}
