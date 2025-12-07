import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:intl/intl.dart';
import '../../providers/app_provider.dart';
import '../../models/category_model.dart';

class AddTransactionScreen extends StatefulWidget {
  const AddTransactionScreen({super.key});

  @override
  State<AddTransactionScreen> createState() => _AddTransactionScreenState();
}

class _AddTransactionScreenState extends State<AddTransactionScreen> {
  final _formKey = GlobalKey<FormState>();
  final _amountController = TextEditingController();
  final _descController = TextEditingController();
  final _dateController = TextEditingController();

  String _type = 'expense';
  int? _selectedCategoryId;
  bool _categoriesLoaded = false;
  String _selectedCurrency = 'USD';
  Map<String, String> _currencies = {};
  bool _currenciesLoaded = false;

  @override
  void initState() {
    super.initState();
    _dateController.text = DateFormat('yyyy-MM-dd').format(DateTime.now());
    _loadCategories();
    _loadCurrencies();
  }

  Future<void> _loadCategories() async {
    final app = Provider.of<AppProvider>(context, listen: false);
    if (app.categories.isEmpty) {
      await app.fetchDashboardData();
    }
    setState(() {
      _categoriesLoaded = true;
    });
  }

  Future<void> _loadCurrencies() async {
    try {
      final app = Provider.of<AppProvider>(context, listen: false);
      final response = await app.getCurrencies();
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        setState(() {
          _currencies = Map<String, String>.from(data);
          if (_currencies.isNotEmpty && !_currencies.containsKey(_selectedCurrency)) {
            _selectedCurrency = _currencies.keys.first;
          }
          _currenciesLoaded = true;
        });
      }
    } catch (e) {
      print("Error loading currencies: $e");
      // Set default currencies if API fails
      setState(() {
        _currencies = {
          'USD': 'US Dollar',
          'EUR': 'Euro',
          'GBP': 'British Pound',
          'TND': 'Tunisian Dinar',
          'CAD': 'Canadian Dollar',
          'MAD': 'Moroccan Dirham',
        };
        _currenciesLoaded = true;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final app = Provider.of<AppProvider>(context);

    final List<Category> filteredCategories = app.categories
        .where((c) => c.type == _type)
        .toList();

    return Scaffold(
      appBar: AppBar(
        title: const Text("Add Transaction"),
        backgroundColor: Colors.purple,
        foregroundColor: Colors.white,
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: ListView(
            children: [
              // Type Selection
              Card(
                child: Padding(
                  padding: const EdgeInsets.all(8.0),
                  child: Row(
                    children: [
                      Expanded(
                        child: ChoiceChip(
                          label: const Text("Expense"),
                          selected: _type == "expense",
                          selectedColor: Colors.red.shade100,
                          onSelected: (selected) {
                            if (selected) {
                              setState(() {
                                _type = "expense";
                                _selectedCategoryId = null;
                              });
                            }
                          },
                        ),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: ChoiceChip(
                          label: const Text("Income"),
                          selected: _type == "income",
                          selectedColor: Colors.green.shade100,
                          onSelected: (selected) {
                            if (selected) {
                              setState(() {
                                _type = "income";
                                _selectedCategoryId = null;
                              });
                            }
                          },
                        ),
                      ),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: _amountController,
                keyboardType: const TextInputType.numberWithOptions(decimal: true),
                decoration: const InputDecoration(
                  labelText: "Amount",
                  border: OutlineInputBorder(),
                  prefixIcon: Icon(Icons.attach_money),
                ),
                validator: (v) {
                  if (v == null || v.isEmpty) {
                    return "Enter amount";
                  }
                  final amount = double.tryParse(v);
                  if (amount == null) {
                    return "Enter a valid number";
                  }
                  if (amount <= 0) {
                    return "Amount must be greater than 0";
                  }
                  return null;
                },
              ),
              const SizedBox(height: 15),
              TextFormField(
                controller: _descController,
                decoration: const InputDecoration(
                  labelText: "Note (Optional)",
                  border: OutlineInputBorder(),
                  hintText: "Add a note about this transaction",
                ),
                maxLines: 2,
              ),
              const SizedBox(height: 15),
              TextFormField(
                controller: _dateController,
                readOnly: true,
                decoration: const InputDecoration(labelText: "Date", border: OutlineInputBorder(), suffixIcon: Icon(Icons.calendar_today)),
                onTap: () async {
                  DateTime? picked = await showDatePicker(
                    context: context,
                    initialDate: DateTime.now(),
                    firstDate: DateTime(2000),
                    lastDate: DateTime(2100),
                  );
                  if (picked != null) {
                    setState(() {
                      _dateController.text = DateFormat('yyyy-MM-dd').format(picked);
                    });
                  }
                },
              ),
              const SizedBox(height: 15),
              _categoriesLoaded && filteredCategories.isEmpty
                  ? const Padding(
                      padding: EdgeInsets.all(8.0),
                      child: Text(
                        "No categories available. Please create categories first.",
                        style: TextStyle(color: Colors.red),
                      ),
                    )
                  : DropdownButtonFormField<int>(
                      value: _selectedCategoryId,
                      decoration: InputDecoration(
                        labelText: "Category",
                        border: const OutlineInputBorder(),
                        hintText: "Select a category",
                        errorText: _selectedCategoryId == null && filteredCategories.isNotEmpty
                            ? "Select a category"
                            : null,
                      ),
                      items: filteredCategories.map((c) {
                        return DropdownMenuItem(
                          value: c.id,
                          child: Text(c.name),
                        );
                      }).toList(),
                      onChanged: (val) {
                        setState(() {
                          _selectedCategoryId = val;
                        });
                      },
                      validator: (v) => v == null ? "Select a category" : null,
                    ),
              const SizedBox(height: 15),
              _currenciesLoaded
                  ? DropdownButtonFormField<String>(
                      value: _selectedCurrency,
                      decoration: const InputDecoration(
                        labelText: "Currency",
                        border: OutlineInputBorder(),
                        prefixIcon: Icon(Icons.attach_money),
                      ),
                      items: _currencies.entries.map((entry) {
                        return DropdownMenuItem(
                          value: entry.key,
                          child: Text("${entry.key} - ${entry.value}"),
                        );
                      }).toList(),
                      onChanged: (val) {
                        setState(() {
                          _selectedCurrency = val!;
                        });
                      },
                    )
                  : const SizedBox(
                      height: 56,
                      child: Center(child: CircularProgressIndicator()),
                    ),
              const SizedBox(height: 24),
              SizedBox(
                height: 50,
                child: ElevatedButton(
                  onPressed: app.isLoading ? null : () async {
                    if (_formKey.currentState!.validate()) {
                      try {
                        final amount = double.parse(_amountController.text);
                        if (amount <= 0) {
                          if (mounted) {
                            ScaffoldMessenger.of(context).showSnackBar(
                              const SnackBar(
                                content: Text("Amount must be greater than 0"),
                                backgroundColor: Colors.red,
                              ),
                            );
                          }
                          return;
                        }

                        bool success = await app.addTransaction(
                          amount,
                          _type,
                          _selectedCategoryId!,
                          _descController.text.isEmpty ? null : _descController.text,
                          _dateController.text,
                          _selectedCurrency,
                        );

                        if (success && mounted) {
                          Navigator.pop(context);
                          ScaffoldMessenger.of(context).showSnackBar(
                            const SnackBar(
                              content: Text("Transaction added successfully!"),
                              backgroundColor: Colors.green,
                            ),
                          );
                        } else if (mounted) {
                          final errorMsg = app.errorMessage ?? "Failed to add transaction. Please check your connection and try again.";
                          ScaffoldMessenger.of(context).showSnackBar(
                            SnackBar(
                              content: Text(errorMsg),
                              backgroundColor: Colors.red,
                              duration: const Duration(seconds: 4),
                            ),
                          );
                        }
                      } catch (e) {
                        if (mounted) {
                          ScaffoldMessenger.of(context).showSnackBar(
                            SnackBar(
                              content: Text("Error: ${e.toString()}"),
                              backgroundColor: Colors.red,
                              duration: const Duration(seconds: 4),
                            ),
                          );
                        }
                      }
                    }
                  },
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.purple,
                    foregroundColor: Colors.white,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(12),
                    ),
                  ),
                  child: app.isLoading
                      ? const SizedBox(
                          height: 20,
                          width: 20,
                          child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white),
                        )
                      : const Text(
                          "Save Transaction",
                          style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                        ),
                ),
              )
            ],
          ),
        ),
      ),
    );
  }
}
