import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/app_provider.dart';
import '../../models/transaction_model.dart';

class TransactionListScreen extends StatelessWidget {
  const TransactionListScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final app = Provider.of<AppProvider>(context);

    return Scaffold(
      appBar: AppBar(title: const Text("All Transactions")),
      body: app.transactions.isEmpty
          ? const Center(child: Text("No transactions found"))
          : ListView.builder(
        padding: const EdgeInsets.all(16.0),
        itemCount: app.transactions.length,
        itemBuilder: (context, index) {
          final TransactionModel t = app.transactions[index];
          final isIncome = t.type == 'income';
          return Card(
            margin: const EdgeInsets.only(bottom: 10),
            child: ListTile(
              leading: CircleAvatar(
                backgroundColor: isIncome ? Colors.green[100] : Colors.red[100],
                child: Icon(
                  isIncome ? Icons.arrow_downward : Icons.arrow_upward,
                  color: isIncome ? Colors.green : Colors.red,
                ),
              ),
              title: Text(t.note ?? "No Description"),
              subtitle: Text("${t.date} • ${t.category?.name ?? 'Uncategorized'}"),
              trailing: Text(
                "${isIncome ? '+' : '-'} \$${t.amount}",
                style: TextStyle(
                  color: isIncome ? Colors.green : Colors.red,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ),
          );
        },
      ),
    );
  }
}
