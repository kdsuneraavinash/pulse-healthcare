import 'package:flutter/material.dart';
import 'package:pulse_healthcare/login.dart';
import 'package:flutter/services.dart';
import 'package:pulse_healthcare/uigradient.dart';

void main() {
  // Set Status bar colors
  SystemChrome.setSystemUIOverlayStyle(SystemUiOverlayStyle(
    systemNavigationBarColor: UiGradient.primaryColor,
    statusBarColor: UiGradient.primaryColor,
  ));
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'MediKit',
      // Theme Definition
      theme: ThemeData(
        primaryColor: UiGradient.primaryColor,
        accentColor: UiGradient.accentColor,
        iconTheme: IconThemeData(color: Colors.white),
      ),
      home: LoginScreen(),
    );
  }
}
