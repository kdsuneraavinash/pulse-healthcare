import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:pulse_healthcare/logic/theme.dart';
import 'package:pulse_healthcare/login.dart';

import 'logic/user_manager.dart';

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider<ThemeStash>(
      builder: (_) => ThemeStash(),
      child: ChangeNotifierProvider<UserManager>(
        builder: (_) => UserManager(),
        child: Builder(
          builder: (context) => MaterialApp(
                title: 'MediKit',
                // Theme Definition
                theme: ThemeData(
                  primaryColor: Provider.of<ThemeStash>(context).primaryColor,
                  accentColor: Provider.of<ThemeStash>(context).accentColor,
                  iconTheme: IconThemeData(
                    color: Provider.of<ThemeStash>(context).iconColor,
                  ),
                ),
                home: LoginScreen()
              ),
        ),
      ),
    );
  }
}
