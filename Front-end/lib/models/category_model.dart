class Category {
  final int id;
  final String name;
  final String type; // 'income' or 'expense'
  final int? userId;

  Category({
    required this.id,
    required this.name,
    required this.type,
    this.userId,
  });

  factory Category.fromJson(Map<String, dynamic> json) {
    return Category(
      id: int.parse(json['id'].toString()),
      name: json['name'] ?? 'Unknown',
      type: json['type'] ?? 'expense',
      userId: json['user_id'] != null ? int.tryParse(json['user_id'].toString()) : null,
    );
  }
}
