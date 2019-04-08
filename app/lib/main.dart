import 'package:flutter/material.dart';
import 'package:pulse_healthcare/home.dart';
import 'package:pulse_healthcare/login.dart';

void main() => runApp(MyApp());

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Flutter Demo',
      theme: ThemeData(
        primaryColor: Colors.black,
        accentColor: Colors.deepPurple,
      ),
      home: LoginScreen(),
    );
  }
}
