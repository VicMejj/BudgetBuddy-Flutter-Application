import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import '../models/transaction_model.dart';
import '../models/category_model.dart';
import '../services/api_service.dart';

class AppProvider with ChangeNotifier {
  final ApiService _apiService = ApiService();

  List<TransactionModel> _transactions = [];
  List<Category> _categories = [];
  double _balance = 0.0;
  bool _isLoading = false;
  String? _errorMessage;

  List<TransactionModel> get transactions => _transactions;
  List<Category> get categories => _categories;
  double get balance => _balance;
  bool get isLoading => _isLoading;
  String? get errorMessage => _errorMessage;

  Future<void> fetchDashboardData() async {
    _isLoading = true;
    notifyListeners();

    try {
      final balanceRes = await _apiService.getBalance();
      if (balanceRes.statusCode == 200) {
        final data = jsonDecode(balanceRes.body);
        _balance = double.tryParse(data['balance'].toString()) ?? 0.0;
      }

      final transRes = await _apiService.getTransactions();
      if (transRes.statusCode == 200) {
        final List<dynamic> data = jsonDecode(transRes.body);
        _transactions = data.map((json) => TransactionModel.fromJson(json)).toList();
      }

      final catRes = await _apiService.getCategories();
      if (catRes.statusCode == 200) {
        final List<dynamic> data = jsonDecode(catRes.body);
        _categories = data.map((json) => Category.fromJson(json)).toList();
      }

    } catch (e) {
      print("Error fetching data: $e");
    }

    _isLoading = false;
    notifyListeners();
  }

  // Changed: description -> note, removed type from payload (backend gets it from category)
  Future<bool> addTransaction(double amount, String type, int categoryId, String? note, String date, String currency) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    try {
      final response = await _apiService.addTransaction({
        'amount': amount,
        'category_id': categoryId,
        if (note != null && note.isNotEmpty) 'note': note,
        'date': date,
        'currency': currency,
      });

      if (response.statusCode == 201 || response.statusCode == 200) {
        await fetchDashboardData();
        _isLoading = false;
        _errorMessage = null;
        notifyListeners();
        return true;
      } else {
        // Parse error response
        try {
          final errorData = jsonDecode(response.body);
          _errorMessage = errorData['message'] ?? errorData['error'] ?? 'Failed to create transaction';
          if (errorData['errors'] != null) {
            final errors = errorData['errors'] as Map<String, dynamic>;
            _errorMessage = errors.values.first[0] ?? _errorMessage;
          }
        } catch (e) {
          _errorMessage = 'Server error: ${response.statusCode}';
        }
        print("Transaction creation failed: ${response.statusCode}");
        print("Error response: ${response.body}");
      }
    } catch (e) {
      _errorMessage = 'Network error: ${e.toString()}';
      print("Error adding transaction: $e");
    }
    _isLoading = false;
    notifyListeners();
    return false;
  }

  Future<http.Response> getCurrencies() async {
    return await _apiService.getCurrencies();
  }

  Future<http.Response> convertCurrency(double amount, String from, String to) async {
    return await _apiService.convertCurrency(amount, from, to);
  }
}
