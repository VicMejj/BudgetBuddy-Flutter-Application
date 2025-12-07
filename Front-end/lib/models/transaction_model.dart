import 'category_model.dart';

// Removed: type, description
// Added: note, currency, userId
class TransactionModel {
  final int id;
  final int userId;
  final int categoryId;
  final double amount;
  final String currency;
  final String? note;
  final String date;
  final Category? category;

  TransactionModel({
    required this.id,
    required this.userId,
    required this.categoryId,
    required this.amount,
    required this.currency,
    this.note,
    required this.date,
    this.category,
  });

  factory TransactionModel.fromJson(Map<String, dynamic> json) {
    return TransactionModel(
      id: int.parse(json['id'].toString()),
      userId: int.parse(json['user_id'].toString()),
      categoryId: int.tryParse(json['category_id'].toString()) ?? 0,
      amount: double.tryParse(json['amount'].toString()) ?? 0.0,
      currency: json['currency'] ?? 'USD',
      note: json['note'],
      date: json['date'] ?? '',
      category: json['category'] != null ? Category.fromJson(json['category']) : null,
    );
  }

  String get type => category?.type ?? 'expense';
}
