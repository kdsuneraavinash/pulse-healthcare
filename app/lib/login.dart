import 'package:flare_flutter/flare_actor.dart';
import 'package:flutter/material.dart';
import 'package:pulse_healthcare/home.dart';

class LoginScreen extends StatelessWidget {
  LoginScreen({Key key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Scaffold(
        body: Stack(
          children: <Widget>[
            Container(
              color: Colors.black,
            ),
            Container(
              child: FlareActor(
                "assets/login_screen.flr",
                alignment: Alignment.center,
                fit: BoxFit.cover,
                animation: "rotate",
              ),
            ),
            ScrollConfiguration(
              behavior: NoGlowScrollBehavior(),
              child: Center(
                child: ListView(
                  shrinkWrap: true,
                  children: <Widget>[
                    LoginForm(),
                  ],
                ),
              ),
            ),
            Align(
              alignment: Alignment.bottomCenter,
              child: Padding(
                padding: const EdgeInsets.all(8.0),
                child: Text(
                  "Team Pulse",
                  style: TextStyle(
                      color: Colors.white,
                      fontSize: 15.0,
                      letterSpacing: 2.0,
                      fontWeight: FontWeight.w300),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class LoginForm extends StatefulWidget {
  @override
  _LoginFormState createState() => _LoginFormState();
}

class _LoginFormState extends State<LoginForm> {
  final _formKey = GlobalKey<FormState>();

  Widget _buildFormTextBox(
      String hintText, FormFieldValidator<String> validator,
      [isPassword = false]) {
    return Padding(
      padding: const EdgeInsets.all(8.0),
      child: Center(
        child: Center(
          child: TextFormField(
            obscureText: isPassword,
            decoration: InputDecoration(
              border: OutlineInputBorder(),
              hintText: hintText,
              fillColor: Colors.white,
              filled: true,
              errorStyle: TextStyle(
                color: Colors.white,
              ),
              errorBorder: OutlineInputBorder(
                borderSide: BorderSide(color: Colors.white),
              ),
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
            Padding(
              padding: const EdgeInsets.all(16.0),
              child: Text(
                "MediKit",
                style: TextStyle(
                  fontSize: 72,
                  color: Colors.white,
                  fontWeight: FontWeight.w700,
                ),
              ),
            ),
            _buildFormTextBox("PATIENT-ID", _patientIdValidation),
            _buildFormTextBox("PASSWORD", _passwordIdValidation, true),
            Row(
              mainAxisAlignment: MainAxisAlignment.end,
              children: <Widget>[
                Padding(
                  padding: const EdgeInsets.all(8.0),
                  child: RaisedButton(
                    onPressed: _loginButtonPress,
                    color: Colors.white,
                    child: Padding(
                      padding: const EdgeInsets.all(20.0),
                      child: Text(
                        'Login',
                        style: TextStyle(fontSize: 18, color: Colors.black),
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
      return 'Please enter your PATIENT-ID';
    }
    return null;
  }

  String _passwordIdValidation(String value) {
    if (value.isEmpty) {
      return 'Please enter password';
    }
    return null;
  }

  void _loginButtonPress() {
    Navigator.push(
      context,
      PageRouteBuilder(
        pageBuilder: (_, animation, ___) => FadeTransition(
          opacity: animation,
          child: HomePage(),
        )
      ),
    );
//    if (_formKey.currentState.validate()) {
//      Scaffold.of(context)
//          .showSnackBar(SnackBar(content: Text('Processing Data')));
//    }
  }
}

class NoGlowScrollBehavior extends ScrollBehavior {
  Widget buildViewportChrome(
      BuildContext context, Widget child, AxisDirection axisDirection) {
    return child;
  }
}
