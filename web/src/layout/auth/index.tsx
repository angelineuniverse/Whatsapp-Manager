import { Component } from "react";
import Input from "../../component/input/input";
import Button from "../../component/button/button";
import { login } from "./controller";
class Auth extends Component {
  state: Readonly<{
    email: string | undefined;
    password: string | undefined;
  }>;

  constructor(props: any) {
    super(props);
    this.state = { email: undefined, password: undefined };

    this.auth = this.auth.bind(this);
  }

  async auth() {
    await login({ ...this.state })
      .then((res) => {
        console.log(res, "response");
      })
      .catch((err) => {
        console.log(err);
      });
  }
  render() {
    return (
      <div className="h-screen max-h-screen flex justify-center items-center bg-gray-50">
        <div className="rounded-md shadow-xl border border-gray-200 bg-white p-5 w-3/12">
          <h1 className="uppercase font-bold text-sm">umkm digital</h1>
          <h6 className="mt-6 font-bold text-3xl mb-1">Masuk</h6>
          <p className="text-xs">Masukan informasi email dan password anda</p>
          <div className="mt-5">
            <Input
              label="Email"
              type="text"
              size="medium"
              onValueChange={(value: string) => this.setState({ email: value })}
            />
            <Input
              label="Password"
              type="password"
              className="mt-2 mb-8"
              size="medium"
              onValueChange={(value: string) =>
                this.setState({ password: value })
              }
            />
            <Button
              title="Masuk"
              theme="primary"
              size="medium"
              width="full"
              onClick={this.auth}
            />
          </div>
        </div>
      </div>
    );
  }
}

export default Auth;
