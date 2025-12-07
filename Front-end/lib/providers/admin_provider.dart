import 'dart:convert';
import 'package:flutter/material.dart';
import '../models/user_model.dart';
import '../services/api_service.dart';

class AdminProvider with ChangeNotifier {
  final ApiService _apiService = ApiService();

  List<User> _users = [];
  bool _isLoading = false;

  List<User> get users => _users;
  bool get isLoading => _isLoading;

  Future<void> fetchUsers() async {
    _isLoading = true;
    notifyListeners();

    try {
      final response = await _apiService.getAllUsers();
      if (response.statusCode == 200) {
        final List<dynamic> data = jsonDecode(response.body);
        _users = data.map((json) => User.fromJson(json)).toList();
      }
    } catch (e) {
      print("Error fetching users: $e");
    }

    _isLoading = false;
    notifyListeners();
  }

  Future<bool> muteUser(int userId) async {
    try {
      final response = await _apiService.muteUser(userId);
      if (response.statusCode == 200) {
        await fetchUsers();
        return true;
      }
    } catch (e) {
      print("Error muting user: $e");
    }
    return false;
  }

  Future<bool> banUser(int userId) async {
    try {
      final response = await _apiService.banUser(userId);
      if (response.statusCode == 200) {
        await fetchUsers();
        return true;
      }
    } catch (e) {
      print("Error banning user: $e");
    }
    return false;
  }

  Future<bool> activateUser(int userId) async {
    try {
      final response = await _apiService.activateUser(userId);
      if (response.statusCode == 200) {
        await fetchUsers();
        return true;
      }
    } catch (e) {
      print("Error activating user: $e");
    }
    return false;
  }

  Future<bool> deleteUser(int userId) async {
    try {
      final response = await _apiService.deleteUser(userId);
      if (response.statusCode == 200) {
        await fetchUsers();
        return true;
      }
    } catch (e) {
      print("Error deleting user: $e");
    }
    return false;
  }
}

