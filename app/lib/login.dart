import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:pulse_healthcare/home.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';

import 'logic/theme.dart';
import 'logic/user_manager.dart';

class LoginScreen extends StatelessWidget {
  LoginScreen({Key key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    // Store media query hight to avoid lookup latency
    double mediaQueryHeight = MediaQuery.of(context).size.height;

    return Stack(
      children: <Widget>[
        Scaffold(
          appBar: AppBar(
            title: Text("Login"),
            centerTitle: true,
            leading: Provider.of<UserManager>(context).pending
                ? Center(child: CircularProgressIndicator())
                : Icon(FontAwesomeIcons.medkit),
            actions: <Widget>[
              IconButton(
                onPressed: Provider.of<ThemeStash>(context).nextTheme,
                icon: Icon(Icons.palette),
              ),
            ],
          ),
          body: ListView(
            children: <Widget>[
              SizedBox(
                height: mediaQueryHeight / 3,
                child: _buildTopBanner(context),
              ),
              LoginForm(),
            ],
          ),
        ),
        IgnorePointer(
          ignoring: Provider.of<UserManager>(context).userDataRetrieved,
          child: AnimatedOpacity(
            duration: Duration(seconds: 1),
            opacity:
                Provider.of<UserManager>(context).userDataRetrieved ? 0 : 1,
            child: Container(
              color: Theme.of(context).primaryColor,
              child: Center(child: CircularProgressIndicator()),
            ),
          ),
        ),
      ],
    );
  }

  /// Top Half of the screen
  Widget _buildTopBanner(BuildContext context) {
    return Container(
      alignment: Alignment.center,
      color: Theme.of(context).primaryColor,
      padding: const EdgeInsets.all(16.0),
      child: Text(
        "MediKit",
        style: TextStyle(
          fontSize: 72,
          color: Colors.white,
          fontWeight: FontWeight.w700,
        ),
      ),
    );
  }
}

/// Login Form (Stateful) - Bottom Half of the screen
class LoginForm extends StatefulWidget {
  @override
  _LoginFormState createState() => _LoginFormState();
}

class _LoginFormState extends State<LoginForm> {
  // Form key to validate form
  final _formKey = GlobalKey<FormState>();
  bool _isPrefsRetrieved = false;

  // Text Controllers to retrieve text
  TextEditingController _usernameController;
  TextEditingController _passwordController;

  void initState() {
    super.initState();
    _usernameController = new TextEditingController();
    _passwordController = new TextEditingController();
  }

  void takePrefsAndLoadNextPage() async {
    Map<String, String> map = await Provider.of<UserManager>(context)
        .setPreviousUsernameAndPassword();
    if (map == null) {
      // loginFailed("Prefs Not Found");
      return;
    }

    print(map.values.toList());
    String result = await Provider.of<UserManager>(context)
        .loginAndGetAllData(map['username'], map['password']);
    if (result == null) {
      loginSuccessful();
    } else {
      loginFailed(result);
    }
  }

  /// Builds a text box for the login form
  Widget _buildFormTextBox({
    String labelText,
    FormFieldValidator<String> validator,
    TextEditingController textEditingController,
    IconData icon,
    isPassword = false,
  }) {
    if (!_isPrefsRetrieved) {
      takePrefsAndLoadNextPage();
      _isPrefsRetrieved = true;
    }

    return Padding(
      padding: const EdgeInsets.all(8.0),
      child: Center(
        child: Center(
          child: TextFormField(
            controller: textEditingController,
            obscureText: isPassword,
            decoration: InputDecoration(
              prefixIcon: Icon(icon),
              border: OutlineInputBorder(),
              labelText: labelText,
            ),
            validator: validator,
          ),
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      alignment: Alignment.center,
      margin: EdgeInsets.all(16.0),
      child: Form(
        key: _formKey,
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: <Widget>[
            _buildFormTextBox(
              labelText: "Patient ID",
              validator: _patientIdValidation,
              textEditingController: _usernameController,
              icon: FontAwesomeIcons.userAlt,
              isPassword: false,
            ),
            _buildFormTextBox(
              labelText: "Password",
              validator: _passwordIdValidation,
              textEditingController: _passwordController,
              icon: FontAwesomeIcons.key,
              isPassword: true,
            ),
            Row(
              mainAxisAlignment: MainAxisAlignment.end,
              children: <Widget>[
                Padding(
                  padding: const EdgeInsets.all(8.0),
                  child: RaisedButton(
                    onPressed: _loginButtonPress,
                    color: Theme.of(context).accentColor,
                    child: Padding(
                      padding: const EdgeInsets.all(20.0),
                      child: Text(
                        'Login',
                        style: TextStyle(fontSize: 18, color: Colors.white),
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  String _patientIdValidation(String value) {
    if (value.isEmpty) {
      return 'Please enter your Patient ID';
    }
    return null;
  }

  String _passwordIdValidation(String value) {
    if (value.isEmpty) {
      return 'Please enter password';
    }
    return null;
  }

  void _loginButtonPress() async {
    if (_formKey.currentState.validate()) {
      String result = await Provider.of<UserManager>(context)
          .loginAndGetAllData(
              _usernameController.text, _passwordController.text);
      if (result == null) {
        loginSuccessful();
      } else {
        loginFailed(result);
      }
    }
  }

  void loginFailed(String message) {
    showDialog(
      context: context,
      builder: (_) => LoginFailedDialog(errorMessage: message),
    );
  }

  void loginSuccessful() {
    Navigator.pushReplacement(
      context,
      PageRouteBuilder(
        pageBuilder: (_, animation, ___) => FadeTransition(
              opacity: animation,
              child: HomePage(),
            ),
      ),
    );
  }
}

class LoginFailedDialog extends StatelessWidget {
  final String errorMessage;

  LoginFailedDialog({Key key, this.errorMessage}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return SimpleDialog(
      backgroundColor: Provider.of<ThemeStash>(context).warningColor,
      children: <Widget>[
        Container(
          margin: EdgeInsets.symmetric(vertical: 24.0),
          alignment: Alignment.center,
          child: Text(
            errorMessage,
            style: TextStyle(fontSize: 16.0),
            textAlign: TextAlign.center,
          ),
        ),
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 24),
          child: OutlineButton(
            onPressed: () {
              Navigator.pop(context);
            },
            child: Padding(
              padding: const EdgeInsets.all(8.0),
              child: Text(
                'OK',
                style: TextStyle(fontSize: 18),
              ),
            ),
          ),
        )
      ],
      title: Align(
        alignment: Alignment.center,
        child: Text("Login Failed"),
      ),
    );
  }
}
