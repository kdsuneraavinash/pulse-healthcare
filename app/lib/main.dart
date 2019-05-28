import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:pulse_healthcare/logic/theme/theme_stash.dart';
import 'package:pulse_healthcare/ui/login.dart';

import 'package:pulse_healthcare/logic/api_controller/api_controller.dart';

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider<ThemeStash>(
      builder: (_) => ThemeStash(),
      child: ChangeNotifierProvider<APIController>(
        builder: (_) => APIController(),
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
