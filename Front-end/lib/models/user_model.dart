class User {
  final int id;
  final String name;
  final String email;
  final String? role;
  final String? status;

  User({
    required this.id,
    required this.name,
    required this.email,
    this.role,
    this.status,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: int.parse(json['id'].toString()),
      name: json['name'] ?? '',
      email: json['email'] ?? '',
      role: json['role'],
      status: json['status'] ?? 'active',
    );
  }

  bool get isAdmin => role == 'admin';
  bool get isActive => status == 'active';
  bool get isMuted => status == 'muted';
  bool get isBanned => status == 'banned';
}
